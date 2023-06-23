<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("ConnectionManager", "Model");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("CookieUtility", "modules/cakeutils/utility");
App::uses("CookieDTO", "modules/cakeutils/classes");
App::uses("CookieStatusDTO", "modules/cakeutils/classes");

class CookiemanagerUI extends AppGenericUI {

    function __construct() {
        parent::__construct("CookiemanagerUI");
        $this->localefile = "cookie";
        $this->obj = null;
    }

    function update($flgPreference = null, $flgStatistic = null, $flgMarketing = null, $flgNotClassified = null, $flgNecessary = true) {
        $this->LOG_FUNCTION = "update";
        try {
            if (empty($flgPreference)) {
                $flgPreference = false;
            }
            if (empty($flgStatistic)) {
                $flgStatistic = false;
            }
            if (empty($flgMarketing)) {
                $flgMarketing = false;
            }
            if (empty($flgNotClassified)) {
                $flgNotClassified = false;
            }
            if (empty($flgNecessary)) {
                $flgNecessary = true;
            }
            CookieUtility::update($flgPreference, $flgStatistic, $flgMarketing, $flgNotClassified, $flgNecessary);
            $this->ok();
            return true;
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_COOKIES_UPDATE");
            return false;
        }
    }

    function cookies($type = null) {
        $this->LOG_FUNCTION = "cookies";
        try {
            $list = null;
            if (!empty($type)) {
                $list = CookieUtility::listInstancedType($type);
            } else {
                $list = array();
                $list1 = CookieUtility::listInstancedType(EnumCookieType::NECESSARY);
                $list2 = CookieUtility::listInstancedType(EnumCookieType::PREFERENCE);
                $list3 = CookieUtility::listInstancedType(EnumCookieType::STATISTIC);
                $list4 = CookieUtility::listInstancedType(EnumCookieType::MARKETING);
                $list5 = CookieUtility::listInstancedType(EnumCookieType::NOT_CLASSIFIED);
                $this->unionList($list, $list1, $list2, $list3, $list4, $list5);
            }
            $this->ok();
            return $this->json ? json_encode($list, true) : $list;
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_COOKIES_LIST");
            return "";
        }
    }

    function cookie($key = null, $type = null) {
        $this->LOG_FUNCTION = "cookie";
        try {
            if (empty($key)) {
                DelegateUtility::paramsNull($this, "ERROR_COOKIE_NOT_FOUND");
                return "";
            }
            $cookie = null;
            if (empty($type)) {
                $list = array();
                $list1 = CookieUtility::listInstancedType(EnumCookieType::NECESSARY);
                $list2 = CookieUtility::listInstancedType(EnumCookieType::PREFERENCE);
                $list3 = CookieUtility::listInstancedType(EnumCookieType::STATISTIC);
                $list4 = CookieUtility::listInstancedType(EnumCookieType::MARKETING);
                $list5 = CookieUtility::listInstancedType(EnumCookieType::NOT_CLASSIFIED);
                $this->unionList($list, $list1, $list2, $list3, $list4, $list5);
                foreach ($list as $cookieInstance) {
                    if ($cookieInstance->name == $key) {
                        $cookie = $cookieInstance;
                        break;
                    }
                }
            } else {
                $cookie = CookieUtility::readInstancedType($key, EnumCookieType::NECESSARY);
            }
            if (empty($cookie)) {
                DelegateUtility::errorInternal($this, "OBJECT_NULL", "ERROR_COOKIE_NOT_FOUND", null, "LOG_COOKIE_NOT_FOUND", array($key));
                return "";
            }
            $this->ok();
            return $this->json ? json_encode($cookie) : $cookie;
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_COOKIES_UPDATE");
            return "";
        }
    }

    function status($key = null, $type = null) {
        $this->LOG_FUNCTION = "status";
        try {
            $cookieStatus = new CookieStatusDTO();
            if (!empty($key) && !empty($type)) {
                $value = CookieUtility::checkCookieType($key, $type);
                $this->decodeType($cookieStatus, $type, $value);
                $cookieStatus->value = $value;
            } elseif (empty($key) && !empty($type)) {
                $value = CookieUtility::isActiveType($type);
                $this->decodeType($cookieStatus, $type, $value);
                $cookieStatus->value = $value;
            } else {
                $cookieStatus->isNecessary = CookieUtility::isActiveType(EnumCookieType::NECESSARY);
                $cookieStatus->isPreference = CookieUtility::isActiveType(EnumCookieType::PREFERENCE);
                $cookieStatus->isStatistic = CookieUtility::isActiveType(EnumCookieType::STATISTIC);
                $cookieStatus->isMarketing = CookieUtility::isActiveType(EnumCookieType::MARKETING);
                $cookieStatus->isNotClassified = CookieUtility::isActiveType(EnumCookieType::NOT_CLASSIFIED);
            }
            $this->ok();
            return $this->json ? json_encode($cookieStatus) : $cookieStatus;
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_COOKIES_STATUS");
            return "";
        }
    }

    // utils
    private function decodeType(CookieStatusDTO &$cookieStatus, $type, $value) {
        switch ($type) {
        case EnumCookieType::NECESSARY:
            $cookieStatus->isNecessary = $value;
            break;
        case EnumCookieType::PREFERENCE:
            $cookieStatus->isPreference = $value;
            break;
        case EnumCookieType::STATISTIC:
            $cookieStatus->isStatistic = $value;
            break;
        case EnumCookieType::MARKETING:
            $cookieStatus->isMarketing = $value;
            break;
        case EnumCookieType::NOT_CLASSIFIED:
            $cookieStatus->isNotClassified = $value;
            break;
        default:
            break;
        }
    }
    private function unionList(&$list, $necessary, $preference, $statistic, $marketing, $notClassified) {
        foreach ($necessary as $key => $value) {
            array_push($list, $value);
        }
        foreach ($preference as $key => $value) {
            array_push($list, $value);
        }
        foreach ($statistic as $key => $value) {
            array_push($list, $value);
        }
        foreach ($marketing as $key => $value) {
            array_push($list, $value);
        }
        foreach ($notClassified as $key => $value) {
            array_push($list, $value);
        }
    }

}