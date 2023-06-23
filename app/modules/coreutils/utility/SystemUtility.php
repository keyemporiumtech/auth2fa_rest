<?php
App::uses("FileUtility", "modules/coreutils/utility");
/**
 * Utility che gestisce operazioni di sistema basate su php
 *
 * @author Giuseppe Sassone
 */
class SystemUtility {

    // -------------- CAKE
    static function checkModule($moduleName) {
        return FileUtility::existDir(ROOT . "/app/modules/{$moduleName}");
    }

    static function addValueToProperty($object, $name, $val = true) {
        if (property_exists($object, $name)) {
            $object->{$name} = $val;
        }
    }

    // class functions
    static function castObject($obj, $class) {
        $cast = new $class();
        foreach (get_object_vars($obj) as $key => $name) {
            $cast->$key = $name;
        }
        return $cast;
    }

    static function callMethod($obj, $function, $parameters = array()) {
        if (method_exists($obj, $function)) {
            call_user_func_array(array(
                $obj,
                $function,
            ), $parameters);
        }
    }

    // NETWORK FUNCTIONS
    static function browser() {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        //    echo "UA=$user_agent<br/>";
        $browsers = array(
            'Edge' => array(
                'Edge',
                'Edge/([0-9\.]*)',
            ),
            'Chrome' => array(
                'Google Chrome',
                'Chrome/(.*)\s',
            ),
            'MSIE' => array(
                'Explorer',
                'MSIE\s([0-9\.]*)',
            ),
            'Firefox' => array(
                'Firefox',
                'Firefox/([0-9\.]*)',
            ),
            'Safari' => array(
                'Safari',
                'Safari/([0-9\.]*)',
            ),
            'Opera' => array(
                'Opera',
                'Opera/([0-9\.]*)',
            ),
        );

        $browser_details = array();

        foreach ($browsers as $browser => $browser_info) {
            if (preg_match('@' . $browser . '@i', $user_agent)) {
                $browser_details['name'] = $browser_info[0];
                preg_match('@' . $browser_info[1] . '@i', $user_agent, $version);
                $browser_details['version'] = $version[1];
                break;
            } else {
                $browser_details['name'] = 'Unknown';
                $browser_details['version'] = 'Unknown';
            }
        }
        //echo 'Browser: '.$browser_details['name'].' Version: '.$browser_details['version'];
        return $browser_details;
    }

    static function getOS() {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        //    echo "UA=$user_agent<br/>";
        $platforms = array(
            'windows nt 10' => array(
                'Windows 10',
            ),
            'windows nt 6.3' => array(
                'Windows 8.1',
            ),
            'windows nt 6.2' => array(
                'Windows 8',
            ),
            'windows nt 6.1' => array(
                'Windows 7',
            ),
            'windows nt 6.0' => array(
                'Windows Vista',
            ),
            'windows nt 5.2' => array(
                'Windows Server 2003/XP x64',
            ),
            'windows nt 5.1' => array(
                'Windows XP',
            ),
            'windows xp' => array(
                'Windows XP',
            ),
            'windows nt 5.0' => array(
                'Windows 2000',
            ),
            'windows me' => array(
                'Windows ME',
            ),
            'win98' => array(
                'Windows 98',
            ),
            'win95' => array(
                'Windows 95',
            ),
            'win16' => array(
                'Windows 3.11',
            ),
            'macintosh|mac os x' => array(
                'Mac OS X',
            ),
            'mac_powerpc' => array(
                'Mac OS 9',
            ),
            'linux' => array(
                'Linux',
            ),
            'ubuntu' => array(
                'Ubuntu',
            ),
            'iphone' => array(
                'iPhone',
            ),
            'ipod' => array(
                'iPod',
            ),
            'ipad' => array(
                'iPad',
            ),
            'android' => array(
                'Android',
            ),
            'blackberry' => array(
                'BlackBerry',
            ),
            'webos' => array(
                'Mobile',
            ),
        );

        $platform_details = array();

        foreach ($platforms as $platform => $platform_info) {
            if (preg_match('@' . $platform . '@i', $user_agent)) {
                $platform_details['os'] = $platform_info[0];
                break;
            } else {
                $platform_details['os'] = 'Unknown';
            }
        }
        return $platform_details;
    }

    static function getPlatormInfo() {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        preg_match_all("([(](.*?)[)])", $user_agent, $risultato);
        return !empty($risultato[1][0]) ? $risultato[1][0] : 'Unknown';
    }

    static function getPlatormCompose() {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        preg_match_all("([(](.*?)[)])", $user_agent, $risultato);
        $platform_details = array();
        $platforms = explode(";", $risultato[1][0]);
        $i = 0;
        $platform_info = "";
        foreach ($platforms as $platform):
            if ($i == 0) {
                $platform_details['os'] = $platform;
            } else {
                $platform_info .= " " . $platform;
            }

            $i++;
        endforeach
        ;
        $platform_details['info'] = $platform_info;
        return $platform_details;
    }

    static function getIPClient() {
        if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',') > 0) {
                $addr = explode(",", $_SERVER['HTTP_X_FORWARDED_FOR']);
                return trim($addr[0]);
            } else {
                return $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }

    /**
     * controlla se una url esiste e risponde
     * @param string $host
     */
    static function ping($host = null) {
        exec(sprintf('ping -c 1 -W 5 %s', escapeshellarg($host)), $res, $rval);
        return $rval == 0;
    }

    // TIMING LOADING
    /**
     * Ritorna il tempo attuale scompattando microtime in un array di due valori, poi sommati:
     * il primo sono i millisecondi
     * il secondo sono i secondi
     * @return float
     */
    static function timing() {
        $tempo = microtime();
        $tempo = explode(" ", $tempo);
        $tempo[0] = floatval($tempo[0]);
        $tempo[1] = floatval($tempo[1]);
        return ($tempo[0] + $tempo[1]);
    }

    /**
     * Sulla base di un tempo iniziale passato come parametro, ritorna la string del tempo trascorso
     * @param float $init_timing
     * @return string
     */
    static function printEndTiming($init_timing) {
        $end_timing = SystemUtility::timing();
        $delay = $end_timing - $init_timing;
        return substr($delay, 0, 6);
    }

    static function getallheaders() {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', strtolower(str_replace('_', ' ', substr($name, 5))))] = $value;
            }
        }
        return $headers;
    }
}
