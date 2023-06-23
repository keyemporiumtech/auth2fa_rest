<?php
App::uses("CryptingConfig", "modules/crypting/config");
App::uses("EnumTypeCrypt", "modules/crypting/config");
App::uses("Security", "Utility");
App::uses("Defaults", "Config/system");
App::uses("Crypting", "Config/system");

class CryptingUtility {

    /**
     * Ritorna una stringa che sostituisce i caratteri presenti nell'array "avoidChars" con i corrispettivi in posizione dell'array "replaceAvoidChars"
     * @param string $data stringa da ripulire dei caratteri "avoidChars"
     * @param array $avoidChars array dei caratteri da ripulire
     * @param array $replaceAvoidChars array di caratteri da sostituire
     * @return string stringa ripulita dei caratteri "avoidChars" con i corrispettivi in posizione dell'array "replaceAvoidChars"
     */
    public static function evaluateAvoidEncrypt($data, $avoidChars = array(), $replaceAvoidChars = array()) {
        if (empty($avoidChars) || count($avoidChars) == 0) {
            $avoidChars = CryptingConfig::$REPLACE_AVOID_CHARS[0];
        }
        if (empty($replaceAvoidChars) || count($replaceAvoidChars) == 0) {
            $replaceAvoidChars = CryptingConfig::$REPLACE_AVOID_CHARS[1];
        }
        return str_replace($avoidChars, $replaceAvoidChars, $data);
    }

    /**
     * Ritorna una stringa che sostituisce i caratteri presenti nell'array "replaceAvoidChars" con i corrispettivi in posizione dell'array "avoidChars"
     * @param string $data stringa da ripulire dei caratteri "replaceAvoidChars"
     * @param array $avoidChars array di caratteri da sostituire
     * @param array $replaceAvoidChars array dei caratteri da ripulire
     * @return string stringa ripulita dei caratteri "replaceAvoidChars" con i corrispettivi in posizione dell'array "avoidChars"
     */
    public static function evaluateAvoidDecrypt($data, $avoidChars = array(), $replaceAvoidChars = array()) {
        if (empty($avoidChars) || count($avoidChars) == 0) {
            $avoidChars = CryptingConfig::$REPLACE_AVOID_CHARS[0];
        }
        if (empty($replaceAvoidChars) || count($replaceAvoidChars) == 0) {
            $replaceAvoidChars = CryptingConfig::$REPLACE_AVOID_CHARS[1];
        }
        return str_replace($replaceAvoidChars, $avoidChars, $data);
    }

    /**
     * Ritorna una stringa cryptata sostituendo i caratteri in chiave all'array "replaceChars" con i rispettivi valori
     * @param string $data stringa da cryptare
     * @param array $replaceChars array con chiave il carattere da sostiture e con valore la nuova stringa rimpiazzata
     * @param string $flgAvoidChars se true indica che bisogna sostituire i caratteri speciali con altri (di default "true")
     * @param array $avoidChars array di caratteri da ripulire
     * @param array $replaceAvoidChars array dei caratteri da sostituire
     * @return string stringa cryptata
     */
    public static function encrypt($data, $replaceChars = array(), $flgAvoidChars = true, $avoidChars = array(), $replaceAvoidChars = array()) {
        if (empty($replaceChars) || count($replaceChars) == 0) {
            $replaceChars = CryptingConfig::$REPLACE_CHARS;
        }
        $data = (string) $data;
        $length = strlen($data);
        $hash = '';
        for ($i = 0; $i < $length; $i++) {
            if (isset($data[$i])) {
                $hash .= array_key_exists($data[$i], $replaceChars) ? $replaceChars[$data[$i]] : $data[$i];
            }
        }
        return $flgAvoidChars ? CryptingUtility::evaluateAvoidEncrypt($hash, $avoidChars, $replaceAvoidChars) : $hash;
    }

    /**
     * Ritorna una stringa ripulita dal cryptaggio ottenuto sostituendo i caratteri in chiave all'array "replaceChars" con i rispettivi valori
     * @param string $data stringa da decryptare
     * @param array $replaceChars array con chiave il carattere sostituito e con valore la corrispondente sostituzione
     * @param string $flgAvoidChars se true indica che bisogna ripristinare i caratteri speciali (di default "true")
     * @param array $avoidChars array di caratteri da sostituire
     * @param array $replaceAvoidChars array dei caratteri da ripulire
     * @return string stringa decryptata
     */
    public static function decrypt($data, $replaceChars = array(), $flgAvoidChars = true, $avoidChars = array(), $replaceAvoidChars = array()) {
        if (empty($replaceChars) || count($replaceChars) == 0) {
            $replaceChars = CryptingConfig::$REPLACE_CHARS;
        }
        if ($flgAvoidChars) {
            $data = CryptingUtility::evaluateAvoidDecrypt($data, $avoidChars, $replaceAvoidChars);
        }
        $base_encryption_array = array_flip($replaceChars);
        $data = (string) $data;
        $length = strlen($data);
        $string = '';

        for ($i = 0; $i < $length; $i = $i + 3) {
            if (isset($data[$i]) && isset($data[$i + 1]) && isset($data[$i + 2]) && isset($base_encryption_array[$data[$i] . $data[$i + 1] . $data[$i + 2]])) {
                $string .= $base_encryption_array[$data[$i] . $data[$i + 1] . $data[$i + 2]];
            }
        }
        return $string;
    }

    //--------------------RIJNDAEL
    /**
     * Ritorna una string cryptata con algoritmo Rijndael
     * @param string $data stringa da cryptare
     * @param string $key chiave di criptaggio (di default null)
     * @param string $flgAvoidChars se true indica che bisogna sostituire i caratteri speciali con altri (di default "true")
     * @param array $avoidChars array di caratteri da ripulire
     * @param array $replaceAvoidChars array dei caratteri da sostituire
     * @return string stringa cryptata
     */
    public static function encrypt_rijndael($data, $key = null, $flgAvoidChars = true, $avoidChars = array(), $replaceAvoidChars = array()) {
        $secret = Security::rijndael($data, empty($key) ? Defaults::get('RIJNDAEL_KEY') : $key, 'encrypt');
        return $flgAvoidChars ? CryptingUtility::evaluateAvoidEncrypt(base64_encode($secret), $avoidChars, $replaceAvoidChars) : $secret;
    }

    /**
     * Ritorna una stringa decryptata con algoritmo Rijndael
     * @param string $data stringa da decryptare
     * @param string $key chiave di criptaggio (di default null)
     * @param string se true indica che bisogna ripristinare i caratteri speciali (di default "true")
     * @param array $avoidChars array di caratteri da sostituire
     * @param array $replaceAvoidChars array dei caratteri da ripulire
     * @return string stringa decryptata
     */
    public static function decrypt_rijndael($data, $key = null, $flgAvoidChars = true, $avoidChars = array(), $replaceAvoidChars = array()) {
        if ($flgAvoidChars) {
            $data = CryptingUtility::evaluateAvoidDecrypt($data, $avoidChars, $replaceAvoidChars);
        }
        $secret = Security::rijndael(base64_decode($data), empty($key) ? Defaults::get('RIJNDAEL_KEY') : $key, 'decrypt');
        return $secret;
    }

    /**
     * Valida se una stringa cryptata è di tipo Rijndael verificando che abbia una lunghezza di 88 caratteri
     * @param string $data stringa da validare
     * @return boolean true se "data" ha una lunghezza di 88 caratteri
     */
    public static function validateKey_rijndael($data) {
        if (strlen($data) == 88) {
            return true;
        }
        return false;
    }

    //--------------------AES
    /**
     * Ritorna una string cryptata con algoritmo AES
     * @param string $data stringa da cryptare
     * @param string $phrase frase per il cryptaggio (default null)
     * @param string $iv iv per il cryptaggio (default null)
     * @return string stringa cryptata
     */
    public static function encrypt_aes($data, $phrase = null, $iv = null) {
        return Crypting::encrypt_aes($data, empty($phrase) ? Defaults::get('PHRASE_AES') : $phrase, empty($iv) ? Defaults::get('IV_AES') : $iv);
    }

    /**
     * Ritorna una string decryptata con algoritmo AES
     * @param string $data stringa da decryptare
     * @param string $phrase frase per il cryptaggio usato (default null)
     * @param string $iv iv per il cryptaggio usato (default null)
     * @return string stringa decryptata
     */
    public static function decrypt_aes($data, $phrase = null, $iv = null) {
        return Crypting::decrypt_aes($data, empty($phrase) ? Defaults::get('PHRASE_AES') : $phrase, empty($iv) ? Defaults::get('IV_AES') : $iv);
    }

    //--------------------SHA256
    /**
     * Ritorna una string cryptata con algoritmo SHA256
     * @param string $data stringa da cryptare
     * @param string $phrase frase per il cryptaggio (default null)
     * @param string $iv iv per il cryptaggio (default null)
     * @return string stringa cryptata
     */
    public static function encrypt_sha256($data, $phrase = null, $iv = null) {
        return Crypting::encrypt_sha256($data, empty($phrase) ? Defaults::get('PHRASE_SHA256') : $phrase, empty($iv) ? Defaults::get('IV_SHA256') : $iv);
    }

    /**
     * Ritorna una string decryptata con algoritmo SHA256
     * @param string $data stringa da decryptare
     * @param string $phrase frase per il cryptaggio usato (default null)
     * @param string $iv iv per il cryptaggio usato (default null)
     * @return string stringa decryptata
     */
    public static function decrypt_sha256($data, $phrase = null, $iv = null) {
        return Crypting::decrypt_sha256($data, empty($phrase) ? Defaults::get('PHRASE_SHA256') : $phrase, empty($iv) ? Defaults::get('IV_SHA256') : $iv);
    }

    //--------------------COMMONS
    /**
     * Ritorna una stringa cryptata secondo la tipologia "type" (@see EnumTypeCrypt) di cryptaggio scelta
     * @param string $data stringa da cryptare
     * @param type $type tipo di cryptaggio scelto (@see EnumTypeCrypt) (di default INNER)
     * @param array $keys array delle chiavi utili al cryptaggio [key,replaceChars,flgAvoidChars,avoidChars,replaceAvoidChars,phrase,iv] (di default array())
     * @return string una stringa cryptata secondo la tipologia "type" (@see EnumTypeCrypt) di cryptaggio scelta
     */
    public static function encryptByType($data, $type = null, $keys = array()) {
        switch ($type) {

        case EnumTypeCrypt::RIJNDAEL:
            $key = array_key_exists("key", $keys) ? $keys['key'] : null;
            $flgAvoidChars = array_key_exists("flgAvoidChars", $keys) ? $keys['flgAvoidChars'] : false;
            $avoidChars = array_key_exists("avoidChars", $keys) ? $keys['avoidChars'] : array();
            $replaceAvoidChars = array_key_exists("replaceAvoidChars", $keys) ? $keys['replaceAvoidChars'] : array();
            return CryptingUtility::encrypt_rijndael($data, $key, $flgAvoidChars, $avoidChars, $replaceAvoidChars);
        case EnumTypeCrypt::AES:
            $phrase = array_key_exists("phrase", $keys) ? $keys['phrase'] : null;
            $iv = array_key_exists("iv", $keys) ? $keys['iv'] : null;
            return CryptingUtility::encrypt_aes($data, $phrase, $iv);
        case EnumTypeCrypt::SHA256:
            $phrase = array_key_exists("phrase", $keys) ? $keys['phrase'] : null;
            $iv = array_key_exists("iv", $keys) ? $keys['iv'] : null;
            return CryptingUtility::encrypt_sha256($data, $phrase, $iv);
        case EnumTypeCrypt::INNER:
        default:
            $replaceChars = array_key_exists("replaceChars", $keys) ? $keys['replaceChars'] : array();
            $flgAvoidChars = array_key_exists("flgAvoidChars", $keys) ? $keys['flgAvoidChars'] : false;
            $avoidChars = array_key_exists("avoidChars", $keys) ? $keys['avoidChars'] : array();
            $replaceAvoidChars = array_key_exists("replaceAvoidChars", $keys) ? $keys['replaceAvoidChars'] : array();
            return CryptingUtility::encrypt($data, $replaceChars, $flgAvoidChars, $avoidChars, $replaceAvoidChars);
        }
    }

    /**
     * Ritorna una stringa decryptata secondo la tipologia "type" (@see EnumTypeCrypt) di cryptaggio usata
     * @param string $data stringa da decryptare
     * @param type $type tipo di cryptaggio usato (@see EnumTypeCrypt) (di default INNER)
     * @param array $keys array delle chiavi utili al cryptaggio usato [key,replaceChars,flgAvoidChars,avoidChars,replaceAvoidChars,phrase,iv] (di default array())
     * @return string una stringa decryptata secondo la tipologia "type" (@see EnumTypeCrypt) di cryptaggio usata
     */
    public static function decryptByType($data, $type, $keys = array()) {
        switch ($type) {
        case EnumTypeCrypt::RIJNDAEL:
            $key = array_key_exists("key", $keys) ? $keys['key'] : null;
            $flgAvoidChars = array_key_exists("flgAvoidChars", $keys) ? $keys['flgAvoidChars'] : false;
            $avoidChars = array_key_exists("avoidChars", $keys) ? $keys['avoidChars'] : array();
            $replaceAvoidChars = array_key_exists("replaceAvoidChars", $keys) ? $keys['replaceAvoidChars'] : array();
            return CryptingUtility::decrypt_rijndael($data, $key, $flgAvoidChars, $avoidChars, $replaceAvoidChars);
        case EnumTypeCrypt::AES:
            $phrase = array_key_exists("phrase", $keys) ? $keys['phrase'] : null;
            $iv = array_key_exists("iv", $keys) ? $keys['iv'] : null;
            return CryptingUtility::decrypt_aes($data, $phrase, $iv);
        case EnumTypeCrypt::SHA256:
            $phrase = array_key_exists("phrase", $keys) ? $keys['phrase'] : null;
            $iv = array_key_exists("iv", $keys) ? $keys['iv'] : null;
            return CryptingUtility::decrypt_sha256($data, $phrase, $iv);
        case EnumTypeCrypt::INNER:
        default:
            $replaceChars = array_key_exists("replaceChars", $keys) ? $keys['replaceChars'] : array();
            $flgAvoidChars = array_key_exists("flgAvoidChars", $keys) ? $keys['flgAvoidChars'] : false;
            $avoidChars = array_key_exists("avoidChars", $keys) ? $keys['avoidChars'] : array();
            $replaceAvoidChars = array_key_exists("replaceAvoidChars", $keys) ? $keys['replaceAvoidChars'] : array();
            return CryptingUtility::decrypt($data, $replaceChars, $flgAvoidChars, $avoidChars, $replaceAvoidChars);
        }
    }

    /**
     * Ritorna un array di stringhe cryptate secondo la tipologia "type" (@see EnumTypeCrypt) di cryptaggio scelta
     * @param array $list array di stringhe da cryptare
     * @param type $type tipo di cryptaggio scelto (@see EnumTypeCrypt) (di default INNER)
     * @param array $keys array delle chiavi utili al cryptaggio [key,replaceChars,flgAvoidChars,avoidChars,replaceAvoidChars,phrase,iv] (di default array())
     * @return string un array di stringhe cryptate secondo la tipologia "type" (@see EnumTypeCrypt) di cryptaggio scelta
     */
    public static function encryptArrayByType($list = array(), $type = null, $keys = array()) {
        $clone = array();
        foreach ($list as $key => $el) {
            $clone = CryptingUtility::encryptByType($el, $type, $keys);
        }
        return $clone;
    }

    /**
     * Ritorna un array di stringhe decryptate secondo la tipologia "type" (@see EnumTypeCrypt) di cryptaggio usata
     * @param string $data stringa da decryptare
     * @param type $type tipo di cryptaggio usato (@see EnumTypeCrypt) (di default INNER)
     * @param array $keys array delle chiavi utili al cryptaggio usato [key,replaceChars,flgAvoidChars,avoidChars,replaceAvoidChars,phrase,iv] (di default array())
     * @return string array di stringhe decryptate secondo la tipologia "type" (@see EnumTypeCrypt) di cryptaggio usata
     */
    public static function decryptArrayByType($list = array(), $type, $keys = array()) {
        $clone = array();
        foreach ($list as $key => $el) {
            $clone[$key] = CryptingUtility::decryptByType($el, $type, $keys);
        }
        return $clone;
    }
}