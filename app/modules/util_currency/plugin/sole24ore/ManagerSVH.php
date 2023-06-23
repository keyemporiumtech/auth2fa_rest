<?php
App::uses("Enables", "Config/system");
App::uses("Codes", "Config/system");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("TranslatorUtility", "modules/cakeutils/utility");
App::uses("RestSVH", "modules/util_currency/plugin/sole24ore");
App::uses("FileUtility", "modules/coreutils/utility");

class ManagerSVH {

    public static function cache($from, $to) {
        $folder = WWW_ROOT . "files/cache_svh/";
        $filename = "{$folder}/latest_" . date('Ymd') . ".json";
        if (!file_exists($filename)) {
            array_map('unlink', array_filter((array)glob("{$folder}*")));
            $rateConvert = RestSVH::convert($from, $to);

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
                        MessageUtility::logMessage(Codes::get("INFO_GENERIC"), "rate plugin sole24ore preso dalla cache", "log_currency", "currency", MessageUtility::logSource("ManagerSVH", "cache"));
                    }
                    return $el["rate"];
                } elseif ($el["to"] == $from && $el["from"] == $to && !empty($el["rate"])) {
                    if (Enables::get("log_plugin")) {
                        MessageUtility::logMessage(Codes::get("INFO_GENERIC"), "rate plugin sole24ore preso dalla cache", "log_currency", "currency", MessageUtility::logSource("ManagerSVH", "cache"));
                    }
                    return (1 / $el["rate"]);
                }
            }
            $rateConvert = RestSVH::convert($from, $to);

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
            ), "sole24ore"), EnumResponseCode::NO_CONTENT);
        }
        $rateConvert = ManagerSVH::cache($currencyFrom, $currencyTo);

        if (empty($rateConvert) || $rateConvert == 0) {
            return 0;
        }

        return $rate * $rateConvert;
    }
}