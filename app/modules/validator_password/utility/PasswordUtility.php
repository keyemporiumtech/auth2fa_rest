<?php

class PasswordUtility {

    static function validateAlmostNumber($password) {
        return preg_match("/\d/", $password);
    }

    static function validateAlmostUpper($password) {
        return preg_match("/[A-Z]/", $password);
    }

    static function validateAlmostLower($password) {
        return preg_match("/[a-z]/", $password);
    }

    static function validateAlmostAlpha($password) {
        return preg_match("/\W/", $password);
    }

    static function validateLength($password, $min = 5, $max = 10) {
        return (strlen($password) < $min || strlen($password) > $max) ? 0 : 1;
    }
}