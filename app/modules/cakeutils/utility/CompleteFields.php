<?php

App::uses("AppGenericBS", "modules/cackeutils/classes");
App::uses("StringUtility", "modules/coreutils/utility");
App::uses("ArrayUtility", "modules/coreutils/utility");
App::uses('EnumQueryLike', 'modules/cakeutils/config');

class CompleteFields {

    static function complete(AppGenericBS $bs, $belongs = array(), $virtualfields = array(), $flags = array(), $properties = array(), $groups = array(), $likegroups = null) {
        CompleteFields::putBelongs($bs, $belongs);
        CompleteFields::putVirtualFields($bs, $virtualfields);
        CompleteFields::setFlags($bs, $flags);
        CompleteFields::setProperties($bs, $properties);
        CompleteFields::putGroups($bs, $groups, empty($likegroups) ? EnumQueryLike::PRECISION : $likegroups);
    }

    static function putBelongs(AppGenericBS $bs, $belongs = array()) {
        if (count($belongs) > 0) {
            foreach ($belongs as $belong) {
                $dao = $bs->dao;
                if (StringUtility::contains($belong, ".")) {
                    $split = explode(".", $belong);
                    $i = 0;
                    foreach ($split as $val) {
                        if ($i == count($split) - 1) {
                            $bs->addBelongsTo($val, $dao);
                        } else {
                            $dao = $dao->{$val};
                        }
                        $i++;
                    }
                } else {
                    $bs->addBelongsTo($belong, $dao);
                }
            }
        }
    }

    static function putVirtualFields(AppGenericBS $bs, $virtualfields = array()) {
        if (count($virtualfields) > 0) {
            foreach ($virtualfields as $virtualfield) {
                $dao = $bs->dao;
                if (StringUtility::contains($virtualfield, ".")) {
                    $split = explode(".", $virtualfield);
                    $i = 0;
                    foreach ($split as $val) {
                        if ($i == count($split) - 1) {
                            $bs->addVirtualField($val, $dao);
                        } else {
                            $dao = $dao->{$val};
                        }
                        $i++;
                    }
                } else {
                    $bs->addVirtualField($virtualfield, $dao);
                }
            }
        }
    }

    static function setFlags(AppGenericBS $bs, $flags = array()) {
        if (count($flags) > 0) {
            foreach ($flags as $flag) {
                $dao = $bs->dao;
                if (StringUtility::contains($flag, ".")) {
                    $split = explode(".", $flag);
                    $i = 0;
                    foreach ($split as $val) {
                        if ($i == count($split) - 1) {
                            $dao->{$val} = true;
                        } else {
                            $dao = $dao->{$val};
                        }
                        $i++;
                    }
                } else {
                    $dao->{$flag} = true;
                }
            }
        }
    }

    static function setProperties(AppGenericBS $bs, $properties = array()) {
        if (count($properties) > 0) {
            foreach ($properties as $key => $value) {
                $dao = $bs->dao;
                if (StringUtility::contains($key, ".")) {
                    $split = explode(".", $key);
                    $i = 0;
                    foreach ($split as $val) {
                        if ($i == count($split) - 1) {
                            $dao->{$val} = $value;
                        } else {
                            $dao = $dao->{$val};
                        }
                        $i++;
                    }
                } else {
                    $dao->{$key} = $value;
                }
            }
        }
    }

    static function evalConditionVirtualfields(AppGenericBS $bs, $conditionKey, $conditionVal, &$conditions = array()) {
        $dao = $bs->dao;
        if (StringUtility::contains($conditionKey, ".")) {
            $split = explode(".", $conditionKey);
            $i = 0;
            foreach ($split as $val) {
                if ($i == count($split) - 1) {
                    $pairVf = explode("__", $val);
                    $conditions["(" . $dao->virtualFields[$pairVf[0]] . ")" . $pairVf[1]] = $conditionVal;
                } else {
                    $dao = $dao->{$val};
                }
                $i++;
            }
        } else {
            $pairVf = explode("__", $conditionKey);
            $conditions["(" . $dao->virtualFields[$pairVf[0]] . ")" . $pairVf[1]] = $conditionVal;
        }
    }

    static function evalOrderVirtualfields(AppGenericBS $bs, $orderKey, $orderVal, &$orders = array()) {
        $dao = $bs->dao;
        if (StringUtility::contains($orderKey, ".")) {
            $split = explode(".", $orderKey);
            $i = 0;
            foreach ($split as $val) {
                if ($i == count($split) - 1) {
                    $pairVf = explode("__", $val);
                    $orders["(" . $dao->virtualFields[$pairVf[0]] . ")"] = $orderVal;
                } else {
                    $dao = $dao->{$val};
                }
                $i++;
            }
        } else {
            $pairVf = explode("__", $orderKey);
            $orders["(" . $dao->virtualFields[$pairVf[0]] . ")"] = $orderVal;
        }
    }

    static function putGroups(AppGenericBS $bs, $groups = array(), $type = null) {
        if (!ArrayUtility::isEmpty($groups)) {
            $dao = $bs->dao;
            $conditions = array(
                "Grouprelation.tableid = " . $bs->className . ".id",
                "Grouprelation.tablename = '" . $dao->useTable . "'",
            );

            if (empty($type) || $type == EnumQueryLike::PRECISION) {
                array_push($conditions, "Grouprelation.groupcod IN (" . ArrayUtility::getStringByList($groups, false, ",", "'") . ")");
            } else {
                foreach ($groups as $group) {
                    $like = "";
                    switch ($type) {
                    case EnumQueryLike::LEFT:
                        $like = "'%$group'";
                        break;
                    case EnumQueryLike::RIGHT:
                        $like = "'$group%'";
                        break;
                    case EnumQueryLike::LEFT_RIGHT:
                        $like = "'%$group%'";
                        break;
                    }
                    if (!empty($like)) {
                        array_push($conditions, "Grouprelation.groupcod LIKE " . $like);
                    }
                }
            }

            $bs->addJoin("grouprelations", "Grouprelation", "INNER", $conditions);
        }
    }
}
