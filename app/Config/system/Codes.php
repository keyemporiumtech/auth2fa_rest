<?php
require_once ROOT . "/app/Config/system/cores.php";

class Codes {

    public static function get($key) {
        $json = Cores::readJson(ROOT . "" . DS . "app" . DS . "Config" . DS . "json" . DS . "applicationcodes.json");
        if (!Cores::isEmpty($json)) {
            return $json[$key];
        }
        return null;
    }
}
