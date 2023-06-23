<?php
require_once ROOT . "/app/Config/system/cores.php";
require_once ROOT . "/app/Config/system/Crypting.php";
class Defaults {

    static function get($key) {
        switch ($key) {
        case "db_name":
        case "db_host":
        case "db_user":
        case "db_password":
        case "secret_key":
        case "mail_host":
        case "mail_port":
        case "mail_user":
        case "mail_pwd":
        case "mail_pwd_crypt_type":
        case "inner_name":
        case "inner_secret":
            return Defaults::manageCryptingValue($key);
        case "RIJNDAEL_KEY":
        case "PHRASE_AES":
        case "IV_AES":
        case "PHRASE_SHA256":
        case "IV_SHA256":
            return Defaults::manageKeys($key);
        default:
            return Configure::read("DEFAULT_" . $key);
        }

    }

    static function manageCryptingValue($key) {
        $value = Configure::read("DEFAULT_" . $key);
        $phraseCRY = Configure::read("DEFAULT_PHRASE_SHA256");
        $ivCRY = Configure::read("DEFAULT_IV_SHA256");
        $PHRASE = Crypting::specialDecrypt($phraseCRY);
        $IV = Crypting::specialDecrypt($ivCRY);
        return Crypting::simpleDecrypt($value, $PHRASE, $IV);
    }

    static function manageKeys($key) {
        $value = Configure::read("DEFAULT_" . $key);
        return Crypting::specialDecrypt($value);
    }
}
