<?php
App::uses('EnumQueryLike', 'modules/cakeutils/config');
App::uses('EnumQuerySign', 'modules/cakeutils/config');
App::uses('EnumQueryOperator', 'modules/cakeutils/config');
App::uses('EnumQueryType', 'modules/cakeutils/config');
App::uses('ObjKeyValue', 'modules/cakeutils/classes');
App::uses("ConnectionManager", "Model");

/**
 * Utility per la gestione del database
 *
 * @author Giuseppe Sassone
 */
class DBUtility {

    static function logAllQueries($db_name) {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource($db_name);
        $logs = $dbo->getLog();
        $lastLog = end($logs['log']);
        foreach ($logs['log'] as $lastLog) {
            debug($lastLog['query']);
        }
    }

    static function switchDatabase($config = null) {
        try {
            // debug($config);
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource('default');
            $dbo->disconnect();
            ConnectionManager::drop('default');
            if (empty($config)) {
                // come back to default
                $dbconfig = new DATABASE_CONFIG();
                $config = $dbconfig->default;
            }
            ConnectionManager::create('default', $config);
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource('default');
            $dbo->connect();
            $dbo->cacheSources = false;
            // LogUtility::info("SWICTH_DATABASE", "switch to " . $config ['database']);
            // LogUtility::info("SWICTH_DATABASE", "new default " . ConnectionManager::getDataSource('default')->config['database']);
            // debug("switch to " . $config ['database']);
            // debug("new default " . ConnectionManager::getDataSource('default')->config['database']);
        } catch (MissingDatabaseException $e) {
            throw new Exception("MissingDatabaseException: " . $e->getMessage());
        }
    }

    //----------------OPERATORS
    static function addAndToArray(&$array, $conditions = array()) {
        $array['AND'] = $conditions;
    }
    static function addOrToArray(&$array, $conditions = array()) {
        $array['OR'] = $conditions;
    }
    static function addNotToArray(&$array, $conditions = array()) {
        $array['NOT'] = $conditions;
    }

    // utility
    static function getOffsetByLimitAndPage($limit, $page) {
        if ($page == 1) {
            return 0;
        } else {
            return (($page - 1) * $limit);
        }
    }

    static function getStringLimitOffsetForPagination($limit, $page) {
        if ($page == 1) {
            return $limit;
        } else {
            return "$limit OFFSET " . (($page - 1) * $limit);
        }
    }

    //builder conditions
    static function buildOperatorByCondition($operator, $arrayConditions = array()) {
        switch ($operator) {
        case EnumQueryOperator::OP_OR:
            $key = "OR";
            $value = DBUtility::buildArrayByConditionChildren($arrayConditions);
            break;
        case EnumQueryOperator::OP_NOT:
            $key = "NOT";
            $value = DBUtility::buildArrayByConditionChildren($arrayConditions);
            break;
        case EnumQueryOperator::OP_OR_OR:
            $key = "OR";
            $value = DBUtility::buildArrayByConditionChildren($arrayConditions, true);
            break;
        case EnumQueryOperator::OP_AND:
            $key = "AND";
            $value = DBUtility::buildArrayByConditionChildren($arrayConditions);
            break;
        default:
            return null;
        }

        return new ObjKeyValue($key, $value);
    }

    static function buildArrayByConditionChildren($arrayConditions = array(), $exclusion = false) {
        $list = array();
        foreach ($arrayConditions as $condition) {
            if (is_array($condition)) {
                $condition = (object) $condition;
            }
            if (empty($condition->children)) {
                $obj = null;
                if (!empty($condition->sign)) {
                    $obj = DBUtility::getSignCondition($condition->key, $condition->value, $condition->sign);
                } else if (!empty($condition->like)) {
                    $obj = DBUtility::getLikeCondition($condition->key, $condition->value, $condition->like);
                } else if (!empty($condition->between)) {
                    $obj = DBUtility::getBetweenCondition($condition->key, $condition->between[0], $condition->between[1]);
                } else {
                    $obj = DBUtility::getSimpleCondition($condition->key, $condition->value);
                }
            } else {
                $obj = DBUtility::buildOperatorByCondition($condition->operator, $condition->children);
            }
            if (!empty($obj)) {
                if (!$exclusion) {
                    $list[$obj->key] = $obj->value;
                } else {
                    $newList = array();
                    $newList[$obj->key] = $obj->value;
                    array_push($list, $newList);
                }
            }
        }
        return $list;
    }

    static function buildAndArrayCondition($arrayConditions = array(), $exclusion = false) {
        $list = array();
        foreach ($arrayConditions as $condition) {
            if (is_array($condition)) {
                $condition = (object) $condition;
            }
            if (empty($condition->children)) {
                $obj = null;
                $obj = DBUtility::getSimpleCondition($condition->key, $condition->value);
                debug($obj);
            } else {
                $obj = DBUtility::buildOperatorByCondition($condition->operator, $condition->children);
            }
            if (!empty($obj)) {
                if (!$exclusion) {
                    $list[$obj->key] = $obj->value;
                } else {
                    $newList = array();
                    $newList[$obj->key] = $obj->value;
                    array_push($list, $newList);
                }
            }
        }
        debug($list);
        return $list;
    }

    static function getSimpleCondition($key, $value) {
        $json = '{"key": "' . $key . '", "value": "' . $value . '"}';
        return json_decode($json);
    }

    static function getLikeCondition($key, $value, $type = EnumQueryLike::PRECISION) {
        $newValue = '';
        switch ($type) {
        case EnumQueryLike::LEFT:
            $newValue = '%' . $value;
            break;
        case EnumQueryLike::RIGHT:
            $newValue = $value . '%';
            break;
        case EnumQueryLike::LEFT_RIGHT:
            $newValue = '%' . $value . '%';
            break;
        default:
            $newValue = $value;
            break;
        }
        $newKey = $key . " LIKE";
        $json = '{"key": "' . $newKey . '", "value": "' . $newValue . '"}';
        return json_decode($json);
    }

    static function getSignCondition($key, $value, $sign = EnumQuerySign::NOTHING) {
        $newKey = $key . " " . $sign;
        $json = '{"key": "' . $newKey . '", "value": "' . $value . '"}';
        return json_decode($json);
    }

    static function getBetweenCondition($key, $start, $end) {
        $newKey = $key . " BETWEEN ? AND ? ";
        $newValue = "[$start,$end]";
        $json = '{"key": "' . $newKey . '", "value": ' . $newValue . '}';
        return json_decode($json);
    }
}
