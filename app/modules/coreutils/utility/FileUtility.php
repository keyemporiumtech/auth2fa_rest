<?php
App::uses("CoreutilsConfig", "modules/coreutils/config");
App::uses("PageUtility", "modules/coreutils/utility");
App::uses("ArrayUtility", "modules/coreutils/utility");
App::uses("StringUtility", "modules/coreutils/utility");
App::uses("MathUtility", "modules/coreutils/utility");

/**
 * Utility per la gestione di files
 *
 * @author Giuseppe Sassone
 */
class FileUtility {

    //------------------------OPERATIONS
    /**
     * Copia un file "FILE" in una specifica directory "DIR" e con il nome "FILENAME"
     * @param string $DIR directory in cui copiare il file
     * @param string $FILENAME nome del file copiato, inclusa l'estension
     * @param string $FILE percorso origiale del file da copiare, incluso il nome e l'estension
     * @return boolean true se il file è stato correttamente copiato
     */
    public static function loadGenericFile($DIR, $FILENAME, $FILE) {
        if (move_uploaded_file($FILE, $DIR . $FILENAME)) {
            return true;
        }
        return false;
    }

    /**
     * Rimuove un file o una directory
     * @param string $path percorso del file o directory da rimuovere
     */
    public static function unlink($path) {
        if (file_exists($path)) {
            unlink($path);
        }
    }

    /**
     * Cancella una lista di files e/o directory
     * @param array $list lista di files e/o directory
     */
    public static function deleteList($list = array()) {
        foreach ($list as $file) {
            FileUtility::unlink($file);
        }
    }

    /**
     * Cancella una directory e tutto il suo contenuto
     * @param string $dirPath directory da cancellare
     * @return true se la directory è stata cancellata
     */
    public static function deleteDirTmp($dirPath) {
        if (StringUtility::contains($dirPath, "\\")) {
            $dirPath = str_replace("\\", CoreutilsConfig::$SLASH, $dirPath);
        }
        if (!is_dir($dirPath)) {
            return false;
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != CoreutilsConfig::$SLASH) {
            $dirPath .= CoreutilsConfig::$SLASH;
        }
        $files = scandir($dirPath);
        foreach ($files as $file) {
            if ($file == '.' || $file == '..') {
                continue;
            }

            if (is_dir($dirPath . $file)) {
                FileUtility::deleteDir($dirPath . $file);
            } else {
                if ($dirPath . $file != __FILE__) {
                    unlink($dirPath . $file);
                }
            }
        }
        rmdir($dirPath);
        return true;
    }

    /**
     * Cancella una directory e tutto il suo contenuto usando il match con la funzione glob
     * @param string $dirPath directory da cancellare
     * @return true se la directory è stata cancellata
     */
    public static function deleteDir($dirPath) {
        if (StringUtility::contains($dirPath, CoreutilsConfig::$BACK_SLASH)) {
            $dirPath = str_replace(CoreutilsConfig::$BACK_SLASH, CoreutilsConfig::$SLASH, $dirPath);
        }
        array_map('unlink', glob("$dirPath/*.*"));
        rmdir($dirPath);
        if (!FileUtility::existDir($dirPath)) {
            return true;
        }
        return false;
    }

    /**
     * Crea un file con uno specifico contenuto
     * @param string $path percorso del file, incluso nome ed estensione
     * @param string $content contenuto del file da creare
     */
    public static function createFileByContent($path, $content) {
        // chmod($path, 0777);
        $file = fopen($path, 'w') or die("can't open file");
        fwrite($file, $content);
        fclose($file);
    }

    /**
     * Aggiunge del contenuto in coda ad un file fisico
     * @param string $path percorso del file, incluso nome ed estensione
     * @param string $content contenuto da aggiungere in coda al file
     */
    public static function appendToFileByPath($path, $content) {
        chmod($path, 0777);
        $file = fopen($path, 'a') or die("can't open file");
        fwrite($file, $content);
        fclose($file);
    }

    /**
     * Ritorna true se esiste la directory "dirPath"
     * @param string $dirPath directory da controllare
     * @return boolean true se esiste la directory "dirPath"
     */
    public static function existDir($dirPath) {
        if (!file_exists($dirPath) && !is_dir($dirPath)) {
            return false;
        }
        return true;
    }

    //------------------------CONTENTS
    /**
     * Ritorna il contenuto di un file in formato UTF-8
     * @param string $fn percorso del file da leggere o url
     * @return string contenuto di "fn" in formato UTF-8
     */
    public static function file_get_contents_utf8($fn) {
        if (StringUtility::contains($fn, "https")) {
            $content = FileUtility::getSSLPage($fn);
        } else {
            $content = file_get_contents($fn);
        }
        return mb_convert_encoding($content, 'UTF-8', mb_detect_encoding($content, 'UTF-8, ISO-8859-1', true));
    }

    /**
     * Ritorna il contenuto di un file da una url di tipo https
     * @param string $url url del file da leggere
     * @return string contenuto di "url"
     */
    public static function getSSLPage($url) {
        $fn = str_replace("https", "http", $url);
        return file_get_contents($fn);
    }

    /**
     * Ritorna il contenuto in base64 di un file fisico
     * @param string $path percorso del file
     * @return string contenuto in base64 di un file fisico
     */
    public static function getBaseContentByPath($path = null) {
        return base64_encode(file_get_contents($path));
    }

    /**
     * Decodifica il content base64 di una stringa html di tipo data solo per le estensioni contenute nell'array "types"
     * @param string $data stringa in formato data html
     * @param array $types lista di estensioni ammesse alla decodifica
     * @throws \Exception Eccezione generata per decodifica fallita
     * @return string content decodificato
     */
    public static function cleanBase64($data, $types = array()) {
        if (preg_match('/^data:(\w+)\/(\w+);base64,/', $data, $type)) {
            $data = substr($data, strpos($data, ',') + 1);

            if (!ArrayUtility::isEmpty($types)) {
                $type = strtolower($type[1]); // jpg, png, gif

                if (!in_array($type, $types)) {
                    throw new \Exception('invalid image type');
                }
            }

            $data = base64_decode($data);

            if ($data === false) {
                throw new \Exception('base64_decode failed');
            }
            return $data;
        } else {
            throw new \Exception('did not match data URI with image data');
        }
    }

    /**
     * Restituisce il source da agganciare come embed di tags html
     * a partire dal content in base64 e dal mimetype di un file
     * @param string $content content in base64
     * @param string $mimetype mimetype del file
     * @return string il source da agganciare come embed di tags html
     */
    public static function getEmbedByContent($content, $mimetype) {
        return "data:" . $mimetype . ";base64," . $content;
    }

    //------------------------INFO
    /**
     * Ritorna l'estensione di un file fisico
     * @param string $FILENAME percorso del file
     * @return string l'estensione del file
     */
    public static function getExtensionFile($FILENAME) {
        if ($FILENAME) {
            $exts = explode("[/\\.]", strtolower($FILENAME));
            $n = count($exts) - 1;
            $exts = $exts[$n];
            return $exts;
        }
    }

    /**
     * Ritorna l'estensione di un file fisico utilizzando la funzione "end"
     * @param string $path percorso del file
     * @return string l'estensione del file
     */
    public static function getExtensionByPath($path = null) {
        $arrFile = explode(".", $path);
        $ext = end($arrFile);
        return $ext;
    }

    /**
     * Converte una dimensione da bytes a KB, MB o GB
     * @param float $size dimensione in bytes
     * @param string $type unità di conversione (KB, MB o GB)
     * @return float dimensione in KB, MB o GB
     */
    public static function getSizeUnits($size, $type) {
        switch ($type) {
        case "KB":
            $filesize = $size * .0009765625; // bytes to KB
            break;
        case "MB":
            $filesize = ($size * .0009765625) * .0009765625; // bytes to MB
            break;
        case "GB":
            $filesize = (($size * .0009765625) * .0009765625) * .0009765625; // bytes to GB
            break;
        default:
            $filesize = $size;
            break;
        }
        if ($filesize <= 0) {
            return 0;
        } else {
            return round($filesize, 2);
        }
    }

    /**
     * Ritorna una stringa che indica la dimensione appropriata (KB, MB o GB) di una misura in bytes
     * @param float $bytes dimensione in bytes
     * @param boolean $flgdecimal se true mostra la misura in decimale, altrimenti intero (default decimale)
     * @return string stringa che indica la dimensione appropriata (KB, MB o GB) di "bytes"
     */
    public static function getTextSizeUnits($bytes, $flgdecimal = true) {
        $decimal = $flgdecimal ? 2 : 0;
        if ($bytes >= 1073741824) {
            $bytes = MathUtility::getStringByDouble($bytes / 1073741824, $decimal) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = MathUtility::getStringByDouble($bytes / 1048576, $decimal) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = MathUtility::getStringByDouble($bytes / 1024, $decimal) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = MathUtility::getStringByDouble($bytes, $decimal) . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = MathUtility::getStringByDouble($bytes, $decimal) . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

    /**
     * Ritorna la dimensione di un file fisico in KB
     * @param string $FILENAME percorso del file
     * @param string $sizeUnit unità di dimensione (di default "KB")
     * @return number la dimensione di un file in "sizeUnit"
     */
    public static function getSizeFile($FILENAME, $sizeUnit = "KB") {
        $size = 0;
        if (file_exists($FILENAME)) {
            $sizeByte = filesize($FILENAME);
            $size = FileUtility::getSizeUnits($sizeByte, "KB");
        }
        return $size;
    }

    public static function getSizeByPath($path, $sizeUnit = null) {
        $size = filesize($path);
        if (!empty($sizeUnit)) {
            return FileUtility::getSizeUnits($size, $sizeUnit);
        }
        return $size;
    }

    public static function getSizeByContent($content, $ext, $sizeUnit = null) {
        $path = WWW_ROOT . "tmp/" . FileUtility::uuid_medium() . "." . $ext;
        FileUtility::createFileByContent($path, $content);
        $size = "";
        if (file_exists($path)) {
            $size = filesize($path);
            if (!empty($sizeUnit)) {
                return FileUtility::getSizeUnits($size, $sizeUnit);
            }
            unlink($path);
        }
        return $size;
    }

    public static function getSizeByUrl($url, $sizeUnit = null) {
        $head = array_change_key_case(get_headers($url, 1));
        $size = isset($head['content-length']) ? $head['content-length'] : 0;
        if (!$size) {
            return false;
        }
        if (!empty($sizeUnit)) {
            return FileUtility::getSizeUnits($size, $sizeUnit);
        }
        if (is_array($size)) {
            foreach ($size as $el) {
                if ($el > 0) {
                    return $el;
                }
            }
        }
        return $size;
    }

    /**
     * Ritorna il nome di un file fisico
     * @param string $FILENAME nome del file
     * @return string il nome di un file fisico
     */
    public static function getNameFile($FIELDNAME) {
        $tmp = explode('.', $FIELDNAME);
        $ext = end($tmp);
        $fileName = str_replace($ext, "", $FIELDNAME);
        return str_replace(".", "", $fileName);
    }

    /**
     * Ritorna il nome di un file da un path
     * @param string $path percorso del file
     * @return string il nome di un file fisico
     */
    public static function getNameByPath($path) {
        $fieldname = basename($path);
        return FileUtility::getNameFile($fieldname);
    }

    /**
     * Ritorna il mime type di un file fisico
     * @param string $path percorso del file
     * @return string mime type del file
     */
    public static function getMimeTypeByPath($path) {
        return mime_content_type($path);
    }

    /**
     * Ritorna il mime type di un file remoto
     * @param string $url percorso remoto del file
     */
    public static function getMimeTypeByUrl($url) {
        $head = array_change_key_case(get_headers($url, 1));
        $mime = isset($head['content-type']) ? $head['content-type'] : 0;
        if (!$mime) {
            return false;
        }
        if (is_array($mime)) {
            return $mime[0];
        }
        return $mime;
    }

    //--------------------------GENERATOR

    /**
     * Ritorna un identificativo in formato "%04X%04X-%04X-%04X-%04X-%04X%04X%04X"
     * @return string identificativo in formato "%04X%04X-%04X-%04X-%04X-%04X%04X%04X"
     */
    public static function uuid() {
        if (function_exists('com_create_guid') == true) {
            return trim(com_create_guid(), '{}');
        }

        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }

    /**
     * Ritorna un identificativo in formato "%04X%04X-%04X%04X%04X"
     * @return string identificativo in formato "%04X%04X-%04X%04X%04X"
     */
    public static function uuid_medium() {
        if (function_exists('com_create_guid') == true) {
            return trim(com_create_guid(), '{}');
        }

        return sprintf('%04X%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }

    /**
     * Ritorna un identificativo in formato "%04X%04X%04X%04X"
     * @return string identificativo in formato "%04X%04X%04X%04X"
     */
    public static function uuid_medium_unique() {
        if (function_exists('com_create_guid') == true) {
            return trim(com_create_guid(), '{}');
        }

        return sprintf('%04X%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }

    /**
     * Ritorna un identificativo in formato "%04X%04X"
     * @return string identificativo in formato "%04X%04X"
     */
    public static function uuid_short() {
        if (function_exists('com_create_guid') == true) {
            return trim(com_create_guid(), '{}');
        }

        return sprintf('%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }

    /**
     * Ritorna una password formata da Maiuscola, Minuscola, Numero e chiave di 3 caratteri
     * @return string password formata da Maiuscola, Minuscola, Numero e chiave di 3 caratteri
     */
    public static function password() {
        return strtoupper(StringUtility::getRandLetter()) . strtolower(StringUtility::getRandLetter()) . StringUtility::getRandNumber() . substr(FileUtility::uuid_short() . strtolower(FileUtility::uuid_short()), 3);
    }

    /**
     * Ritorna un identificatore in formato numerico, con numeri randomici come caratteri
     * e di lunghezza pari a "length"
     * @param int $length numero di caratteri numerici da generare randomicamente
     * @return string identificatore in formato numerico
     */
    public static function uuid_number($length = 6) {
        $number = "";
        for ($i = 0; $i < $length; $i++) {
            $number .= StringUtility::getRandNumber();
        }
        return $number;
    }

    //------------------------FORMS
    /**
     * Carica un file da un form html su un percorso specifico "DIR"
     * @param string $DIR directory su cui caricare il file
     * @param string $FILENAME nome del file da salvare
     * @param string $FIELDNAME nome del campo di input file del form
     * @param string $key se è un form multiplo indica la chiave da considerare (di default null)
     * @return boolean true se il file è stato caricato, false altrimenti
     */
    public static function loadFile($DIR, $FILENAME, $FIELDNAME, $key = null) {
        $rootF1 = $_FILES["$FIELDNAME"]["tmp_name"];
        if ($key != null) {
            $rootF1 = $_FILES["$FIELDNAME"]["tmp_name"][$key];
        }

        if (move_uploaded_file($rootF1, $DIR . $FILENAME)) {
            $RESULT_Upload_Doc = TRUE;
        } else {
            $RESULT_Upload_Doc = FALSE;
        }

        return $RESULT_Upload_Doc;
    }

    /**
     * Ritorna l'estensione di un file caricato da un form html
     * @param string $FIELDNAME nome del campo di input file del form
     * @param string $key se è un form multiplo indica la chiave da considerare (di default null)
     * @return string l'estensione di un file caricato da un form html
     */
    public static function getExtensionLoad($FIELDNAME, $key = null) {
        $nomeF1 = $_FILES["$FIELDNAME"]["name"];
        if ($key != null) {
            $nomeF1 = $_FILES["$FIELDNAME"]["name"][$key];
        }
        $arrFile = explode(".", $nomeF1);
        $ext = end($arrFile);
        $fileName = "";
        //        list($fileName, $ext) = explode(".", $nomeF1);

        return $ext;
    }

    /**
     * Ritorna la dimensione di un file caricato da un form html
     * @param string $FIELDNAME nome del campo di input file del form
     * @param string $key se è un form multiplo indica la chiave da considerare (di default null)
     * @return string la dimensione di un file caricato da un form html
     */
    public static function getSizeLoad($FIELDNAME, $key = null) {
        if ($key != null) {
            return $_FILES["$FIELDNAME"]["size"][$key];
        }
        return $_FILES["$FIELDNAME"]["size"];
    }

    /**
     * Ritorna il nome di un file caricato da un form html
     * @param string $FIELDNAME nome del campo di input file del form
     * @param string $key se è un form multiplo indica la chiave da considerare (di default null)
     * @return string il nome di un file caricato da un form html
     */
    public static function getNameLoad($FIELDNAME, $key = null) {
        $nomeF1 = $_FILES["$FIELDNAME"]["name"];
        if ($key != null) {
            $nomeF1 = $_FILES["$FIELDNAME"]["name"][$key];
        }
        $tmp = explode('.', $nomeF1);
        $ext = end($tmp);
        $fileName = str_replace($ext, "", $nomeF1);
        return str_replace(".", "", $fileName);
    }

    /**
     * Ritorna true se esiste un campo di un form con il name "FIELDNAME"
     * @param string $FIELDNAME nome del campo di un form
     * @return boolean true se esiste un campo di un form con il name "FIELDNAME"
     */
    public static function existFormFile($FIELDNAME) {
        if ($_FILES["$FIELDNAME"]["name"] != "") {
            return TRUE;
        } else {
            return FALSE;
        }

    }

    //------------------------TYPES
    public static function readProperties($path = null) {
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
            $key = substr($line, 0, strpos($line, '='));
            $value = substr($line, strpos($line, '=') + 1, strlen($line));
            if (!empty($key)) {
                $result[$key] = empty($value) ? "" : trim($value);
            }
        }
        return $result;
    }

    public static function writeProperties($path = null, $buffer = null, $append = false) {
        if (empty($path)) {
            throw new Exception("path vuota");
        }
        if (!is_array($buffer) || empty($buffer)) {
            throw new Exception("content vuoto");
        }
        chmod($path, 0777);
        $fp = fopen($path, $append ? 'a' : 'w') or die("can't open file");
        foreach ($buffer as $key => $value) {
            fwrite($fp, $key . "=" . $value . "\n");
        }
        fclose($fp);
    }
    //------------------------CAKE
    /**
     * Mette un file caricato da una form di upload all'interno della cartella temporanea "tmp/" di sistema
     * @param string $field_form nome del campo di input file del form
     * @param string $key se è un form multiplo indica la chiave da considerare (di default null)
     * @return string percorso del file caricato
     */
    public static function putFormFieldToTemporaryFolder($field_form, $key = null) {
        $rootF1 = $_FILES["$field_form"]["tmp_name"];
        if ($key != null) {
            $rootF1 = $_FILES["$field_form"]["tmp_name"][$key];
        }
        $rootTmp = WWW_ROOT . "tmp/" . FileUtility::getNameLoad($field_form, $key) . "." . FileUtility::getExtensionLoad($field_form, $key);
        move_uploaded_file($rootF1, $rootTmp);
        return $rootTmp;
    }

    /**
     * Crea un file con specifico all'interno della cartella temporanea "tmp/" di sistema
     * @param string $fileName nome del file da creare
     * @param string $content contenuto del file da creare
     * @return string percorso del file creato
     */
    public static function putFileToTemporaryFolder($fileName, $content) {
        FileUtility::createFileByContent(WWW_ROOT . "tmp/" . $fileName, $content);
        return WWW_ROOT . "tmp/" . $fileName;
    }

    /**
     * Metodo che ritorna, all'interno di una specifica funzione, in una specifica classe, il nome della funzione
     * @return string nome della funzione corrente
     */
    public static function getCurrentClassFunction() {
        $backtrace = debug_backtrace();
        if (isset($backtrace[1]['function'])) {
            return $backtrace[1]['function'];
        }
        return "";
    }

    public static function getWebrootFile($path, $filesystem = false) {
        if (!$filesystem) {
            return Router::url('/', true) . $path;
        }
        return WWW_ROOT . $path;
    }
}
