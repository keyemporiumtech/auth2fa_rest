<?php
App::uses("Codes", "Config/system");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("TranslatorUtility", "modules/cakeutils/utility");
App::uses("RestBI", "modules/util_currency/plugin/bancaditalia");
App::uses("FileUtility", "modules/coreutils/utility");

class ManagerBI {

    public static function cache() {
        $folder = WWW_ROOT . "files/cache_bi/";
        $filename = "{$folder}/latest_" . date('Ymd') . ".json";
        if (!file_exists($filename)) {
            array_map('unlink', array_filter((array)glob("{$folder}*")));
            $json = RestBI::latest();
            if (!empty($json)) {
                FileUtility::createFileByContent($filename, $json);
                if (Enables::get("log_plugin")) {
                    $objInfo = MessageUtility::messageGeneric(Codes::get("INFO_GENERIC"), "INFO_CACHED_CURRENCIES", "bancaditalia");
                    MessageUtility::logMessageByObject($objInfo, "log_currency", "currency", MessageUtility::logSource("ManagerBI", "cache"));
                }
            }
        }
    }

    public static function read($flgJson = true, $flgArray = false) {
        ManagerBI::cache();
        $folder = WWW_ROOT . "files/cache_bi/";
        $filename = "{$folder}/latest_" . date('Ymd') . ".json";
        if (file_exists($filename)) {
            $json = file_get_contents($filename);
            if (!$flgJson) {
                return json_decode($json, $flgArray);
            }
            return $json;
        }
        return null;
    }

    public static function get($currency) {
        $array = ManagerBI::read(false, true);
        if (!empty($array) && array_key_exists("latestRates", $array)) {
            $latest = $array["latestRates"];
            foreach ($latest as $cur) {
                if ($cur["isoCode"] == strtoupper($currency)) {
                    return $cur;
                }
            }
            $objInternal = MessageUtility::messageInternal("ERROR_LATESTRATES_KEY_NOT_FOUND", "bancaditalia", null, array(
                $currency,
            ));
            MessageUtility::logMessageByObject($objInternal, "log_currency", "currency", MessageUtility::logSource("ManagerBI", "get"));
            return null;
        } elseif (!array_key_exists("latestRates", $array)) {
            $objInternal = MessageUtility::messageInternal("ERROR_LATESTRATES_NOT_EXISTS", "bancaditalia");
            MessageUtility::logMessageByObject($objInternal, "log_currency", "currency", MessageUtility::logSource("ManagerBI", "get"));
        }
        return null;
    }

    public static function convert($currencyFrom, $currencyTo, $rate = 1) {
        $from = strtoupper($currencyFrom);
        $to = strtoupper($currencyTo);
        $elementFrom = ManagerBI::get($from);
        $elementTo = ManagerBI::get($to);
        if (empty($elementFrom) || empty($elementTo)) {
            throw new Exception(TranslatorUtility::__translate_args("EXCEPTION_CONVERT_CURRENCY_NOT_FOUND", array(
                $currencyFrom,
                $currencyTo,
            ), "bancaditalia"), EnumResponseCode::NO_CONTENT);
        }
        if ($to === "EUR" || $to === "USD") {
            return $rate * (1 / ($to == "EUR" ? $elementFrom["eurRate"] : $elementFrom["usdRate"]));
        }

        $rate1 = empty($elementFrom["eurRate"]) || $elementFrom["eurRate"] == "0" ? 0 : (1 / $elementFrom["eurRate"]);
        $rate2 = empty($elementTo["eurRate"]) || $elementTo["eurRate"] == "0" ? 0 : (1 / $elementTo["eurRate"]);

        if ($rate1 == 0 || $rate2 == 0) {
            return 0;
        }

        return $rate * ($rate1 / $rate2);
    }
}