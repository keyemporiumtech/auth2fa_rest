<?php
App::uses("AppModel", "Model");

class Tpcontactreference extends AppModel {
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
        parent::translateValueInField($results, "cod", "title", "tpcontactreference");
        $this->setImageMapping($results);
        return parent::afterFind($results, $primary);
    }

    public function setImageMapping(&$data) {
        foreach ($data as &$obj) {
            if (array_key_exists($this->alias, $obj)) {
                if (!$this->avoidContent && array_key_exists('symbol', $obj[$this->alias])) {
                    $url = WWW_ROOT . "img" . DS . "contactreference" . DS . "" . $obj[$this->alias]['symbol'];
                    $obj[$this->alias]['iconimage'] = "data: " . mime_content_type($url) . ";base64," . FileUtility::getBaseContentByPath($url);
                }
            }
        }
    }
}
