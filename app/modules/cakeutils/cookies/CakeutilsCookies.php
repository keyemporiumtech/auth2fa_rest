<?php
App::uses("CookieUtility", "modules/cakeutils/utility");
App::uses("EnumCookieType", "modules/cakeutils/config");
App::uses("CookieDTO", "modules/cakeutils/classes");
App::uses("DateUtility", "modules/coreutils/utility");
App::uses("TranslatorUtility", "modules/cakeutils/utility");
// specific
App::uses("SystemUtility", "modules/coreutils/utility");

class CakeutilsCookies {

    static function CAKEPHP() {
        $dto = new CookieDTO();
        $dto->name = "CAKEPHP";
        $dto->description = TranslatorUtility::__translate("COOKIE_CAKEPHP", "cakeutils");
        $dto->type = EnumCookieType::NECESSARY;
        $dto->durationDesc = "24h";
        $dto->protocol = "HTTP";
        $dto->hash = true;
        $dto->link = "https://cakephp.org/privacy";
        CookieUtility::instanceCookieType($dto);
        if (CookieUtility::isActiveType($dto->type) && !CookieUtility::checkCookieType($dto->name, $dto->type) && array_key_exists("CAKEPHP", $_COOKIE)) {
            $dto->duration = 1634687362;
            $dto->value = $_COOKIE["CAKEPHP"];
            CookieUtility::addCookieType($dto);
        }
    }

    static function ddc_platform(CookieinnerComponent $cookie) {
        $dto = new CookieDTO();
        $dto->name = "ddc_platform";
        $dto->description = TranslatorUtility::__translate("COOKIE_DDC_PLATFORM", "cakeutils");
        $dto->type = EnumCookieType::STATISTIC;
        $dto->durationDesc = "5h";
        $dto->protocol = null;
        $dto->hash = true;
        CookieUtility::instanceCookieType($dto);
        if (CookieUtility::isActiveType($dto->type) && !CookieUtility::checkCookieType($dto->name, $dto->type)) {
            $obj_platform = array(
                "ip" => SystemUtility::getIPClient(),
                "os" => SystemUtility::getOS(),
                "browser" => SystemUtility::browser(),
            );
            $init = date('Y-m-d H:i:s');
            $end = DateUtility::addToDate($init, 5, "+", "H", "Y-m-d H:i:s");
            $dto->duration = DateUtility::diffDate($init, $end, "s");
            $dto->value = $obj_platform;
            CookieUtility::createCookieType($cookie, $dto);
        } else if (!CookieUtility::isActiveType($dto->type) && CookieUtility::checkCookie($cookie, $dto->name)) {
            CookieUtility::removeCookie($cookie, $dto->name);
        }
    }
}