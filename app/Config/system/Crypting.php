<?php
// bootstrap
class Crypting {

    static function simpleCrypt($value, $phrase, $iv) {
        if (empty($value)) {
            return "";
        }
        $crypted = Crypting::encrypt_sha256($value, $phrase, $iv);
        return base64_encode($crypted);
    }

    static function simpleDecrypt($value, $phrase, $iv) {
        if (empty($value)) {
            return "";
        }
        $decoded = base64_decode($value);
        return Crypting::decrypt_sha256($decoded, $phrase, $iv);
    }

    static function specialCrypt($value) {
        if (empty($value)) {
            return "";
        }
        return base64_encode(base64_encode($value) . "." . base64_encode("BIGSTONE"));
    }

    static function specialDecrypt($value) {
        if (empty($value)) {
            return "";
        }
        $decoded = base64_decode($value);
        $arr = explode(".", $decoded);
        return base64_decode($arr[0]);
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
        // return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, pack("H*", $phrase), $data, MCRYPT_MODE_CBC, pack("H*", $iv)));
        return base64_encode(openssl_encrypt($data, "AES-128-CBC", pack("H*", $phrase), OPENSSL_RAW_DATA, pack("H*", $iv)));
    }

    /**
     * Ritorna una string decryptata con algoritmo AES
     * @param string $data stringa da decryptare
     * @param string $phrase frase per il cryptaggio usato (default null)
     * @param string $iv iv per il cryptaggio usato (default null)
     * @return string stringa decryptata
     */
    public static function decrypt_aes($data, $phrase = null, $iv = null) {
        // return mcrypt_decrypt(MCRYPT_RIJNDAEL_128, pack("H*", $phrase), base64_decode($data), MCRYPT_MODE_CBC, pack("H*", $iv));
        return openssl_decrypt(base64_decode($data), "AES-128-CBC", pack("H*", $phrase), OPENSSL_RAW_DATA, pack("H*", $iv));
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
        $encrypt_method = "AES-256-CBC";
        $key1 = hash('sha256', $phrase);
        $key2 = substr(hash('sha256', $iv), 0, 16);
        return base64_encode(openssl_encrypt($data, $encrypt_method, $key1, 0, $key2));
    }

    /**
     * Ritorna una string decryptata con algoritmo SHA256
     * @param string $data stringa da decryptare
     * @param string $phrase frase per il cryptaggio usato (default null)
     * @param string $iv iv per il cryptaggio usato (default null)
     * @return string stringa decryptata
     */
    public static function decrypt_sha256($data, $phrase = null, $iv = null) {
        $encrypt_method = "AES-256-CBC";
        $key1 = hash('sha256', $phrase);
        $key2 = substr(hash('sha256', $iv), 0, 16);
        return openssl_decrypt(base64_decode($data), $encrypt_method, $key1, 0, $key2);
    }
}
