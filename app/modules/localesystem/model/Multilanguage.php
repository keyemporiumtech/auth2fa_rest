<?php
App::uses("AppModel", "Model");

class Multilanguage extends AppModel {
    public $arrayBelongsTo = array(
        'language_fk' => array(
            'className' => 'Language',
            'foreignKey' => 'languageid',
        ),
    );

    public function afterFind($results, $primary = false) {
        foreach ($results as &$obj) {
            if (array_key_exists($this->alias, $obj)) {
                // replace
                $obj[$this->alias]['languagecod'] = strtolower($obj[$this->alias]['languagecod']);
            }
        }
        return parent::afterFind($results);
    }

}
