<?php
App::uses("Enables", "Config/system");
App::uses("Codes", "Config/system");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("TranslatorUtility", "modules/cakeutils/utility");
App::uses("RestOANDA", "modules/util_currency/plugin/oanda");
App::uses("FileUtility", "modules/coreutils/utility");

class ManagerOANDA {

    public static function cache($from, $to) {
        $folder = WWW_ROOT . "files/cache_oanda/";
        $filename = "{$folder}/latest_" . date('Ymd') . ".json";
        if (!file_exists($filename)) {
            array_map('unlink', array_filter((array) glob("{$folder}*")));
            $rateConvert = RestOANDA::convert($from, $to);

            if (!empty($rateConvert)) {
                $json = array(
                    array(
                        "from" => $from,
                        "to" => $to,
                        "rate" => $rateConvert,
                    ),
                );
                FileUtility::createFileByContent($filename, json_encode($json, true));
                return $rateConvert;
            }
            return 0;
        } else {
            $buffer = file_get_contents($filename);
            $array = json_decode($buffer, true);
            foreach ($array as $el) {
                if ($el["from"] == $from && $el["to"] == $to && !empty($el["rate"])) {
                    if (Enables::get("log_plugin")) {
                        MessageUtility::logMessage(Codes::get("INFO_GENERIC"), "rate plugin oanda preso dalla cache", "log_currency", "currency", MessageUtility::logSource("ManagerSVH", "cache"));
                    }
                    return $el["rate"];
                } elseif ($el["to"] == $from && $el["from"] == $to && !empty($el["rate"])) {
                    if (Enables::get("log_plugin")) {
                        MessageUtility::logMessage(Codes::get("INFO_GENERIC"), "rate plugin oanda preso dalla cache", "log_currency", "currency", MessageUtility::logSource("ManagerSVH", "cache"));
                    }
                    return (1 / $el["rate"]);
                }
            }
            $rateConvert = RestOANDA::convert($from, $to);

            if (!empty($rateConvert)) {
                $json = array(
                    "from" => $from,
                    "to" => $to,
                    "rate" => $rateConvert,
                );
                array_push($array, $json);
                FileUtility::createFileByContent($filename, json_encode($array, true));
                return $rateConvert;
            }
            return 0;
        }
    }

    public static function convert($currencyFrom, $currencyTo, $rate = 1) {
        if (empty($currencyFrom) || empty($currencyTo)) {
            throw new Exception(TranslatorUtility::__translate_args("EXCEPTION_CONVERT_CURRENCY_EMPTY", array(
                $currencyFrom,
                $currencyTo,
            ), "oanda"), EnumResponseCode::NO_CONTENT);
        }
        $rateConvert = ManagerOANDA::cache($currencyFrom, $currencyTo);

        if (empty($rateConvert) || $rateConvert == 0) {
            return 0;
        }

        return $rate * $rateConvert;
    }

    public static function translate($currency, $language = null) {
        if (empty($language)) {
            $language = CakeSession::read('Config.language');
        }
        $currencyObj = ManagerOANDA::getCurrency($currency, $language);
        return $currencyObj['name'];
    }

    public static function currencies($language, $flgJson = false, $flgArray = true) {
        $filename = ROOT . "/app/modules/util_currency/plugin/oanda/json/{$language}.json";
        if (file_exists($filename)) {
            $json = file_get_contents($filename);
            if (!$flgJson) {
                return json_decode($json, $flgArray);
            }
            return $json;
        }
        return null;
    }

    public static function getCurrency($currency, $language) {
        $arr = ManagerOANDA::currencies($language);
        foreach ($arr as $currencyObj) {
            if ($currencyObj['code'] == $currency) {
                return $currencyObj;
            }
        }
        return null;
    }
}
