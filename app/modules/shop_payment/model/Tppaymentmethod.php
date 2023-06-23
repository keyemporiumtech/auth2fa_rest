<?php
App::uses("AppModel", "Model");

/**
 * Entity Tppaymentmethod
 *
 * @author Giuseppe Sassone
 *
 */
class Tppaymentmethod extends AppModel {
    public $onlyUsed = true;
    public $avoidContent = false;

    public function beforeFind($query) {
        parent::beforeFind($query);
        if (!$this->onlyUsed) {
            $query['conditions'][$this->alias . '.flgused'] = "1";
        }
        return $query;
    }

    public function afterFind($results, $primary = false) {
        parent::translateValueInField($results, "cod", "title", "tppaymentmethod");
        return parent::afterFind($results, $primary);
    }
}
