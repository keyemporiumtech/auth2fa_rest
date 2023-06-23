<?php
App::uses("ArrayUtility", "modules/coreutils/utility");
App::uses("SystemUtility", "modules/coreutils/utility");

/**
 * Utility che gestisce il logging di scrittura su file
 *
 * @author Giuseppe Sassone
 *
 */
class LogUtility {

    public static function simpleWrite($logName, $message, $start = false, $spaces = "\n") {
        if ($start) {
            $message = "\n\n{$message}";
        }
        CakeLog::write($logName, $message . $spaces);
    }

    public static function simpleWriteObject($logName, $object, $message = "", $start = false, $spaces = "\n") {
        if ($start) {
            $message = "\n\n{$message}";
        }
        CakeLog::write($logName, $message . ArrayUtility::toPrintStringNewLine($object) . $spaces);
    }

    public static function write($logName, $cod, $message, $start = false, $spaces = "\n") {
        $code = LogUtility::getCode($cod);
        if ($start) {
            $code = "\n\n{$code}";
        }
        CakeLog::write($logName, $code . "\n" . $message . $spaces);
    }

    public static function writeObject($logName, $cod, $object, $message = "", $start = false, $spaces = "\n") {
        $code = LogUtility::getCode($cod);
        if ($start) {
            $code = "\n\n{$code}";
        }
        CakeLog::write($logName, $code . "\n" . $message . ArrayUtility::toPrintStringNewLine($object) . $spaces);
    }

    //utility
    public static function getCode($cod) {
        $code = "[$cod] [UA: " . CakeSession::userAgent() . "] [ID: " . CakeSession::id() . "]\n{[IP: " . SystemUtility::getIPClient() . "] - [OS: " . SystemUtility::getOS()['os'] . "] - [BROWSER: " . SystemUtility::browser()['name'] . "]}";
        //- [" . date('d/m/Y H:i:s') . "]
        return $code;
    }
}