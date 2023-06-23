<?php
App::uses("AppModel", "Model");
App::uses("FileUtility", "modules/coreutils/utility");
App::uses("TranslatorUtility", "modules/cakeutils/utility");
App::uses("LocaleUtility", "modules/localesystem/utility");

/**
 * Entity Language
 *
 * @author Giuseppe Sassone
 */
class Language extends AppModel {
    public $onlyUsed = true;
    public $avoidContent = false;

    public function afterFind($results, $primary = false) {
        $this->setLanguageMapping($results);
        return parent::afterFind($results);
    }

    public function beforeFind($query) {
        parent::beforeFind($query);
        if (!$this->onlyUsed) {
            $query['conditions'][$this->alias . '.flgused'] = "1";
        }
        return $query;
    }

    public function setLanguageMapping(&$data, $field = 'title') {
        foreach ($data as &$obj) {
            if (array_key_exists($this->alias, $obj)) {
                // replace
                $obj[$this->alias]['cod'] = strtolower($obj[$this->alias]['cod']);
                //added fields
                $label = $obj[$this->alias][$field];
                $obj[$this->alias][$field] = TranslatorUtility::__translate($label, "languagesname");
                if (!$this->avoidContent && array_key_exists('symbol', $obj[$this->alias]) && file_exists(WWW_ROOT . "img" . DS . "flags" . DS . "" . $obj[$this->alias]['symbol'])) {
                    $url = WWW_ROOT . "img" . DS . "flags" . DS . "" . $obj[$this->alias]['symbol'];
                    $obj[$this->alias]['iconimage'] = "data: " . mime_content_type($url) . ";base64," . FileUtility::getBaseContentByPath($url);
                }
                //others
                $obj[$this->alias]['country'] = LocaleUtility::getNationByLanguage($obj[$this->alias]['cod']);
                $obj[$this->alias]['language'] = LocaleUtility::getLanguage($obj[$this->alias]['cod']);
                $obj[$this->alias]['locale'] = LocaleUtility::getLanguageSpecific($obj[$this->alias]['cod']);
                $currencyArr = LocaleUtility::getCoupleCurrencyByLanguage($obj[$this->alias]['cod']);
                if (!ArrayUtility::isEmpty($currencyArr) && count($currencyArr) == 2) {
                    $obj[$this->alias]['currencycod'] = $currencyArr[0];
                    $obj[$this->alias]['currencysymbol'] = $currencyArr[1];
                }
            }
        }
    }
}
