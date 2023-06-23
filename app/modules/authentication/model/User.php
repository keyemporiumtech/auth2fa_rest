<?php
App::uses("AppModel", "Model");
App::uses("EnumTypeCrypt", "modules/crypting/config");
App::uses("CryptingUtility", "modules/crypting/utility");
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');

/**
 * Entity User
 *
 * @author Giuseppe Sassone
 *
 */
class User extends AppModel {
    public $avoidEmptyPassword = false;
    public $avoidEmptyPassclean = false;
    public $avoidSavePassword = false;
    public $avoidUsertest = false;

    public $arrayVirtualFields = array(
        'completename' => "CONCAT(User.name, ' ', User.surname)",
    );
    public $decrypts = array(
        'passclean' => EnumTypeCrypt::SHA256,
    );

    public function beforeFind($query) {
        parent::beforeFind($query);
        if ($this->avoidUsertest) {
            $query['conditions'][$this->alias . '.flgtest'] = "0";
        }
        return $query;
    }

    public function afterFind($results, $primary = false) {
        if (!$this->avoidEmptyPassword) {
            foreach ($results as &$obj) {
                if (array_key_exists($this->alias, $obj)) {
                    $obj[$this->alias]['password'] = null;
                }
            }
        }
        if (!$this->avoidEmptyPassclean) {
            foreach ($results as &$obj) {
                if (array_key_exists($this->alias, $obj)) {
                    $obj[$this->alias]['passclean'] = null;
                }
            }
        }
        return parent::afterFind($results);
    }

    public function beforeSave($options = array()) {
        if (!$this->avoidSavePassword && !empty($this->data[$this->alias]['password'])) {
            //crypt byinternal
            $this->data[$this->alias]['passclean'] = CryptingUtility::encryptByType($this->data[$this->alias]['password'], EnumTypeCrypt::SHA256);
            //crypt sha1
            $passwordHasher = new SimplePasswordHasher(array(
                'hashType' => 'sha1',
            ));
            $this->data[$this->alias]['password'] = $passwordHasher->hash($this->data[$this->alias]['password']);
        }
        return parent::beforeSave($options);
    }
}
