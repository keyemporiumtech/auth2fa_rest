<?php
App::uses("AppModel", "Model");

/**
 * Entity Cryptnote
 *
 * @author Giuseppe Sassone
 *
 */
class Cryptnote extends AppModel {
    public $avoidEmptyCrypt = false;
    public $avoidSaveCrypt = false;

    public $decrypts = array(
        'crypt' => EnumTypeCrypt::SHA256,
    );

    public function afterFind($results, $primary = false) {
        if (!$this->avoidEmptyCrypt) {
            foreach ($results as &$obj) {
                if (array_key_exists($this->alias, $obj)) {
                    $obj[$this->alias]['crypt'] = null;
                }
            }
        }
        return parent::afterFind($results);
    }

    public function beforeSave($options = array()) {
        if (!$this->avoidSaveCrypt && !empty($this->data[$this->alias]['crypt'])) {
            //crypt byinternal
            $this->data[$this->alias]['crypt'] = CryptingUtility::encryptByType($this->data[$this->alias]['crypt'], EnumTypeCrypt::SHA256);
        }
        return parent::beforeSave($options);
    }
}
