<?php
App::uses('CookieComponent', 'Controller/Component');
App::uses("EnumTypeCrypt", "modules/crypting/config");
App::uses("CryptingUtility", "modules/crypting/utility");

class CookieinnerComponent extends CookieComponent {

    public $name = '';
    protected $_type = EnumTypeCrypt::AES;

    /**
     * Will allow overriding default encryption method. Use this method
     * in ex: AppController::beforeFilter() before you have read or
     * written any cookies.
     *
     * @param string $type Encryption method
     * @return void
     */
    public function type($type = EnumTypeCrypt::AES) {
        $availableTypes = array(
            EnumTypeCrypt::AES,
            EnumTypeCrypt::RIJNDAEL,
            EnumTypeCrypt::SHA256,
        );
        if (!in_array($type, $availableTypes)) {
            trigger_error(__d('cake_dev', 'You must use sha256, rijndael or aes for cookie encryption type'), E_USER_WARNING);
            $type = 'cipher';
        }
        $this->_type = $type;
    }

    /**
     * Encrypts $value using public $type method in Security class
     *
     * @param string $value Value to encrypt
     * @return string Encoded values
     */
    protected function _encrypt($value) {
        if (is_array($value)) {
            $value = $this->_implode($value);
        }
        if (!$this->_encrypted) {
            return $value;
        }
        $prefix = "Q2FrZQ==.";
        $cipher = CryptingUtility::encryptByType($value, $this->_type);
        return $prefix . base64_encode($cipher);
    }

    /**
     * Decodes and decrypts a single value.
     *
     * @param string $value The value to decode & decrypt.
     * @return string Decoded value.
     */
    protected function _decode($value) {
        $prefix = 'Q2FrZQ==.';
        $pos = strpos($value, $prefix);
        if ($pos === false) {
            return $this->_explode($value);
        }
        $value = base64_decode(substr($value, strlen($prefix)));
        $plain = CryptingUtility::decryptByType($value, $this->_type);
        return $this->_explode($plain);
    }

}