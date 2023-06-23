<?php
App::uses("AppModel", "Model");
App::uses("FileUtility", "modules/coreutils/utility");
App::uses("LocaleUtility", "modules/localesystem/utility");

class Nation extends AppModel {
    public $onlyUsed = true;
    public $avoidContent = false;

    public function afterFind($results, $primary = false) {
        $this->setNationMapping($results);
        return parent::afterFind($results);
    }

    public function beforeFind($query) {
        parent::beforeFind($query);
        if (!$this->onlyUsed) {
            $query['conditions'][$this->alias . '.flgused'] = "1";
        }
        return $query;
    }

    public function setNationMapping(&$data, $field = 'name', $cod = 'cod_iso3166') {
        foreach ($data as &$obj) {
            if (array_key_exists($this->alias, $obj)) {
                if (!empty(CakeSession::check('Config.language'))) {
                    $folder = LocaleUtility::getLanguageSpecific(CakeSession::read('Config.language'));
                    $root = WWW_ROOT . DS . "files" . DS . "nation_language" . DS . $folder;
                    if (!empty($folder) && FileUtility::existDir($root) && array_key_exists($cod, $obj[$this->alias])) {
                        $content = file_get_contents($root . DS . "country.json");
                        $country = json_decode($content, true);
                        $obj[$this->alias][$field] = array_key_exists($obj[$this->alias][$cod], $country) ? $country[$obj[$this->alias][$cod]] : $obj[$this->alias][$field];
                    }
                    if (!$this->avoidContent && array_key_exists('symbol', $obj[$this->alias])) {
                        $url = WWW_ROOT . "img" . DS . "nations" . DS . "" . $obj[$this->alias]['symbol'];
                        $obj[$this->alias]['iconimage'] = "data: " . mime_content_type($url) . ";base64," . FileUtility::getBaseContentByPath($url);
                    }
                }
            }
        }
    }
}
