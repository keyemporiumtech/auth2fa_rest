<?php
App::uses('I18n', 'I18n');
App::uses("Enables", "Config/system");
App::uses("ArrayUtility", "modules/coreutils/utility");
App::uses("LogUtility", "modules/coreutils/utility");

class TranslatorUtility {
    static $TRANSLATE_FILE = null;

    static function __translate($string, $file = null) {
        if (empty($file) && !empty(TranslatorUtility::$TRANSLATE_FILE)) {
            $file = TranslatorUtility::$TRANSLATE_FILE;
        }

        /** @var string */
        $translated = __d($file, $string);
        $translate = mb_convert_encoding($translated, 'UTF-8');
        if (Enables::get("log_translate")) {
            LogUtility::write("translator", "{FILE:" . $file . "} {LANGUAGE: " . CakeSession::read("language") . "}", $string . "= " . $translate);
        }

        return $translate;
    }

    static function __translate_args($string, $args = array(), $file = null) {
        if (empty($file) && !empty(TranslatorUtility::$TRANSLATE_FILE)) {
            $file = TranslatorUtility::$TRANSLATE_FILE;
        }

        /** @var string */
        $translated = __d($file, $string, $args);
        $translate = mb_convert_encoding($translated, 'UTF-8');
        if (Enables::get("log_translate")) {
            LogUtility::write("translator", "{FILE:" . $file . "} {LANGUAGE: " . CakeSession::read("language") . "} {ARGS:" . ArrayUtility::toPrintString($args, false) . "}", $string . "= " . $translate);
        }

        return $translate;
    }

    /**
     * Traduce un campo "field" con il mapping "class_name"+_+"cod" trovato in "file"
     * @param mixed[] $data risultati di una chiamata cake
     * @param string $class_name nome dell'entity cake
     * @param string $file file contenente le chiavi da tradurre
     * @param string $field campo in cui settare il valore tradotto (di default "title")
     * @param string $cod codice contenuto nella chiave da tradurre e concatenato al nome della classe cake (di default "cod")
     */
    static function translateCakeModelFieldByCod(&$data, $class_name, $file, $field = 'title', $cod = 'cod') {
        foreach ($data as &$obj) {
            if (array_key_exists($class_name, $obj) && !empty($obj[$class_name][$cod])) {
                //internalization
                $label = strtoupper(str_replace("_fk", "", $class_name) . "_" . $obj[$class_name][$cod]);
                $obj[$class_name][$field] = TranslatorUtility::__translate($label, $file);
            }
        }
    }

    // ------------ PO

    /**
     * Ritorna tutte le lingue presenti in un'applicazione
     */
    static function getAvailableLanguages() {
        $arrRet = array();
        $dirScan = scandir(ROOT . "/app/Locale");
        foreach ($dirScan as $name) {
            if ($name != "." && $name != "..") {
                array_push($arrRet, $name);
            }
        }
        sort($arrRet);
        return $arrRet;
    }

    /**
     * Ritorna tutti i moduli che hanno po di un'applicazione
     */
    static function getAvailableModules() {
        $arrRet = array();
        $dirScan = scandir(ROOT . "/app/Locale/ita/LC_MESSAGES");
        $module = "";
        foreach ($dirScan as $file) {
            if (!is_dir($file)) {
                $module = TranslatorUtility::getPoModule($file);
                if (!ArrayUtility::contains($arrRet, $module)) {
                    array_push($arrRet, $module);
                }
            }
        }
        sort($arrRet);
        return $arrRet;
    }

    /**
     * Ritorna tutti i file po presenti nell'applicazione
     */
    static function getAllPoFiles() {
        $arrRet = array();
        $dirScan = scandir(ROOT . "/app/Locale/ita/LC_MESSAGES");
        foreach ($dirScan as $file) {
            if (!is_dir($file)) {
                array_push($arrRet, $file);
            }
        }
        return $arrRet;
    }

    /**
     * @param string $module nome del modulo
     * Ritorna tutti i po di un modulo
     */
    static function getAllPoFilesByModule($module) {
        $arrRet = array();
        $dirScan = scandir(ROOT . "/app/Locale/ita/LC_MESSAGES");
        foreach ($dirScan as $file) {
            if (!is_dir($file)) {
                $modulePo = TranslatorUtility::getPoModule($file);
                if ($modulePo == $module) {
                    array_push($arrRet, $file);
                }
            }
        }
        return $arrRet;
    }

    /**
     * Ritorna un array dove la chiave è il file po e il valore la lista delle properties
     */
    static function getAllPoFields() {
        $arrRet = array();
        $arrPo = TranslatorUtility::getAllPoFiles();
        $fields = null;
        foreach ($arrPo as $poFile) {
            $fields = TranslatorUtility::readPropertiesByPo(ROOT . "/app/Locale/ita/LC_MESSAGES/" . $poFile);
            $arrRet[$poFile] = $fields;
        }
        return $arrRet;
    }

    static function getAllPoFieldsGrouped() {
        $arrRet = array();
        $modules = TranslatorUtility::getAvailableModules();
        foreach ($modules as $module) {
            $arrRet[$module] = TranslatorUtility::getAllPoFieldsByModule($module);
        }
        return $arrRet;
    }

    /**
     * @param string $module nome del modulo
     * Ritorna un array dove la chiave è il file po e il valore la lista delle properties di tutti i po del modulo
     */
    static function getAllPoFieldsByModule($module) {
        $arrRet = array();
        $arrPo = TranslatorUtility::getAllPoFilesByModule($module);
        $fields = null;
        foreach ($arrPo as $poFile) {
            $fields = TranslatorUtility::readPropertiesByPo(ROOT . "/app/Locale/ita/LC_MESSAGES/" . $poFile);
            $arrRet[$poFile] = $fields;
        }
        return $arrRet;
    }

    /**
     * @param string $path path del file po
     * Ritorna la lista delle properties di un po
     */
    static function readPropertiesByPo($path = null) {
        if (empty($path)) {
            throw new Exception("path vuota");
        }
        $buffer = file_get_contents($path);
        if (!$buffer) {
            throw new Exception("Errore di lettura properties");
        }
        $result = array();
        $lines = explode("\n", $buffer);
        foreach ($lines as $line) {
            if (str_contains($line, "msgid")) {
                preg_match('/(?<=msgid \")(.*?)(?=\")/', $line, $output_array);
                if (count($output_array) > 0) {
                    array_push($result, $output_array[0]);
                }
            }
        }
        return $result;
    }

    /**
     * @param string $lang lingua da verificare
     * @param string $po path del file po
     * Ritorna true se uno specifico po $po contiene la traduzione della lingua $lang
     */
    static function isPoTranslated($lang, $po) {
        $ret = false;
        $poName = str_replace(".po", "", $po);
        $langName = __d($poName, "FILE_NAME_LAN");
        if (!empty($langName)) {
            $langPo = str_replace($poName . "_", "", $langName);
            if (strtoupper($langPo) == strtoupper($lang)) {
                $ret = true;
            }
        }
        return $ret;
    }

    /**
     * @param string $po path del file po
     * Ritorna il modulo del po
     */
    static function getPoModule($po) {
        $ret = null;
        $poName = str_replace(".po", "", $po);
        $module = __d($poName, "MODULE");
        if (!empty($module)) {
            $ret = $module;
        }
        return $ret;
    }
}
