<?php
require_once ROOT . "/app/Config/system/cores.php";

class Enables {

    public static function get($key) {
        return Configure::read("ENABLE_" . $key);
    }

    public static function isDebug() {
        $json = Cores::readJson(ROOT . "" . DS . "app" . DS . "Config" . DS . "json" . DS . "mode.json");
        if (!Cores::isEmpty($json)) {
            return $json['debug'];
        }
        return false;
    }

    public static function mode() {
        $json = Cores::readJson(ROOT . "" . DS . "app" . DS . "Config" . DS . "json" . DS . "mode.json");
        if (!Cores::isEmpty($json)) {
            return $json['mode'];
        }
        return null;
    }

    public static function isProd() {
        $json = Cores::readJson(ROOT . "" . DS . "app" . DS . "Config" . DS . "json" . DS . "mode.json");
        if (!Cores::isEmpty($json)) {
            return $json['mode'] == "production";
        }
        return false;
    }

    public static function tokens() {
        $json = Cores::readJson(ROOT . "" . DS . "app" . DS . "Config" . DS . "json" . DS . "mode.json");
        if (!Cores::isEmpty($json)) {
            return explode(",", $json['tokens']);
        }
        return null;
    }
}
