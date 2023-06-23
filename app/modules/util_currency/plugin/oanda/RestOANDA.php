<?php
App::uses("Codes", "Config/system");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("CurlUtility", "modules/coreutils/utility");
App::uses("DateUtility", "modules/coreutils/utility");

class RestOANDA {

    static function convert($from, $to) {
        $url = "https://fxds-public-exchange-rates-api.oanda.com/cc-api/currencies";
        if (empty($from) || empty($to)) {
            return null;
        }
        try {

            $range = RestOANDA::getRange();
            $params = array("base" => $from, "quote" => $to, "data_type" => "general_currency_pair", "start_date" => $range['from'], "end_date" => $range['to']);

            $arrayCurl = array();
            CurlUtility::fillOptionsAvoidSSL($arrayCurl);
            $arrayHeader = array(
                'Accept: application/json',
                'Content-type: application/json',
            );
            CurlUtility::fillOptionsHeaders($arrayCurl, $arrayHeader);

            $ch = CurlUtility::createGet($url, $params, $arrayCurl);
            // debug($arrayCurl);
            $response = CurlUtility::execCurlInfo($ch);
            if (empty($response) || empty($response['response'])) {
                $objInternal = MessageUtility::messageInternal("ERROR_CURL_CONVERT", "oanda", Codes::get("PLUGIN_ERROR"));
                MessageUtility::logMessageByObject($objInternal, "log_currency", "currency", MessageUtility::logSource("RestOANDA", "convert"));
                return null;
            }

            $rate = json_decode($response['response'], true);
            $latest = RestOANDA::getLatest($rate['response']);

            // debug($rate['response']);
            // debug(RestOANDA::getLatest($rate['response']));
            return !empty($latest) ? $latest['average_bid'] : null;
        } catch (Exception $e) {
            $objInternal = MessageUtility::messageInternal("ERROR_CURL", "appgenericbs", Codes::get("PLUGIN_ERROR"), array(
                $url,
            ));
            $objException = MessageUtility::messageExceptionByException($e);
            MessageUtility::logMessageByArrayObjects(array(
                $objInternal,
                $objException,
            ), "log_currency", "currency", MessageUtility::logSource("RestOANDA", "convert"));
            return null;
        }
    }

    static function callApi($from, $to, $dtaStart = null, $dtaEnd = null) {
        $url = "https://fxds-public-exchange-rates-api.oanda.com/cc-api/currencies";
        if (empty($from) || empty($to)) {
            return null;
        }
        try {

            $range = RestOANDA::getRangeChart($dtaStart, $dtaEnd);
            $params = array("base" => $from, "quote" => $to, "data_type" => "chart", "start_date" => $range['from'], "end_date" => $range['to']);

            $arrayCurl = array();
            CurlUtility::fillOptionsAvoidSSL($arrayCurl);
            $arrayHeader = array(
                'Accept: application/json',
                'Content-type: application/json',
            );
            CurlUtility::fillOptionsHeaders($arrayCurl, $arrayHeader);

            $ch = CurlUtility::createGet($url, $params, $arrayCurl);
            // debug($arrayCurl);
            $response = CurlUtility::execCurlInfo($ch);
            if (empty($response) || empty($response['response'])) {
                $objInternal = MessageUtility::messageInternal("ERROR_CURL_CONVERT", "oanda", Codes::get("PLUGIN_ERROR"));
                MessageUtility::logMessageByObject($objInternal, "log_currency", "currency", MessageUtility::logSource("RestOANDA", "convert"));
                return null;
            }

            $rate = json_decode($response['response'], true);

            return $rate['response'];
        } catch (Exception $e) {
            $objInternal = MessageUtility::messageInternal("ERROR_CURL", "appgenericbs", Codes::get("PLUGIN_ERROR"), array(
                $url,
            ));
            $objException = MessageUtility::messageExceptionByException($e);
            MessageUtility::logMessageByArrayObjects(array(
                $objInternal,
                $objException,
            ), "log_currency", "currency", MessageUtility::logSource("RestOANDA", "convert"));
            return null;
        }
    }

    /**
     * @param array $list risposta di currencies
     * @return mixed l'oggetto con l'ultima data
     */
    static function getLatest($list = array()) {
        $found = null;
        $lastTime = null;
        foreach ($list as $current) {
            $closedTime = $current['close_time'];
            if (empty($lastTime) || (!empty($lastTime) && DateUtility::endMax($lastTime, $closedTime))) {
                $lastTime = $closedTime;
                $found = $current;
            }
        }
        return $found;
    }

    /**
     * @param string $from a partire da formato Y-m-d (default uno in meno della data $to)
     * @param string $to fino a formato Y-m-d (default data odierna)
     * @return mixed oggetto con coppia variabili from-to
     */
    static function getRange($from = null, $to = null) {
        if (empty($to)) {
            $to = date("Y-m-d");
        }
        if (empty($from)) {
            $from = DateUtility::addToDate("{$to} 00:00:00", 1, "-", "d", "Y-m-d");
        }
        return array("from" => $from, "to" => $to);
    }
    /**
     * @param string $from a partire da formato Y-m-d (default un anno in meno della data $to)
     * @param string $to fino a formato Y-m-d (default data odierna)
     * @return mixed oggetto con coppia variabili from-to
     */
    static function getRangeChart($from = null, $to = null) {
        if (empty($to)) {
            $to = date("Y-m-d");
        }
        if (empty($from)) {
            $from = DateUtility::addToDate("{$to} 00:00:00", 1, "-", "Y", "Y-m-d");
        }
        return array("from" => $from, "to" => $to);
    }
}
