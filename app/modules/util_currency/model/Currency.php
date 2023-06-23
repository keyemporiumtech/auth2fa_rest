<?php
App::uses("AppModel", "Model");
App::uses("Enables", "Config/system");
App::uses("ManagerOANDA", "modules/util_currency/plugin/oanda");

/**
 * Entity Currency
 *
 * @author Giuseppe Sassone
 *
 */
class Currency extends AppModel {
    public $onlyUsed = true;
    public $avoidContent = false;
    public $extension = "png";

    public function afterFind($results, $primary = false) {
        $this->setCurrencyMapping($results);
        return parent::afterFind($results);
    }

    public function beforeFind($query) {
        parent::beforeFind($query);
        if (!$this->onlyUsed) {
            $query['conditions'][$this->alias . '.flgused'] = "1";
        }
        return $query;
    }

    public function setCurrencyMapping(&$data, $field = 'title') {
        foreach ($data as &$obj) {
            if (array_key_exists($this->alias, $obj) && array_key_exists($field, $obj[$this->alias])) {
                //added fields
                if (Enables::get("oanda")) {
                    $cod = $obj[$this->alias]['cod'];
                    $obj[$this->alias][$field] = ManagerOANDA::translate($cod);
                } else {
                    $label = $obj[$this->alias][$field];
                    $obj[$this->alias][$field] = TranslatorUtility::__translate($label, "currencyname");
                }
                if (!$this->avoidContent && array_key_exists('icon', $obj[$this->alias]) && file_exists(WWW_ROOT . "img" . DS . "currencies" . DS . "{$this->extension}" . DS . "" . $obj[$this->alias]['icon'] . ".{$this->extension}")) {
                    $url = WWW_ROOT . "img" . DS . "currencies" . DS . "{$this->extension}" . DS . "" . $obj[$this->alias]['icon'] . ".{$this->extension}";
                    $obj[$this->alias]['iconimage'] = "data: " . mime_content_type($url) . ";base64," . FileUtility::getBaseContentByPath($url);
                }
            }
        }
    }
}
