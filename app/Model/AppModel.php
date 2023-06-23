<?php
App::uses("Model", "Model");
App::uses("Grouprelation", "Model");
App::uses('Defaults', 'Config/system');
App::uses("ArrayUtility", "modules/coreutils/utility");
App::uses("TimezoneUtility", "modules/coreutils/utility");
App::uses("CryptingUtility", "modules/crypting/utility");
App::uses("TranslatorUtility", "modules/cakeutils/utility");

/**
 * Classe che estende il comportamento di un model
 *
 * @author Giuseppe Sassone
 *
 */
class AppModel extends Model {
    public $arrayBelongsTo = array();
    public $arrayHasOne = array();
    public $arrayHasMany = array();
    public $arrayVirtualFields = array();
    public $orders = array();
    /**
     * Campi che vanno cryptati dopo essere stati estratti
     * @var array array di campi dove la chiave è il nome del campo e il valore Ã¨ il tipo di cryptaggio richiesto @see EnumTypeCrypt
     */
    public $crypts = array();
    /**
     * Campi che vanno decryptati dopo essere stati estratti
     * @var array array di campi dove la chiave è il nome del campo e il valore Ã¨ il tipo di decryptaggio richiesto @see EnumTypeCrypt
     */
    public $decrypts = array();
    /**
     * Campi che vanno cryptati prima di salvare i dati
     * @var array array di campi dove la chiave è il nome del campo e il valore Ã¨ il tipo di cryptaggio richiesto @see EnumTypeCrypt
     */
    public $toCrypts = array();
    // flag
    public $flgTimezone = true;
    public $flgEncrypt = true;
    public $flgDecrypt = false; // inizialmente non bisogna decryptare i campi cryptati su db
    public $saveEncrypt = true;
    public $flgAllGroups = false; // usato solo nelle query per estrarre i dati dei gruppi in join con una qualsiasi entity

    // controls
    public $skipBeforeSave = false;

    // translate
    public $fileI18n = null;
    public $fieldsI18n = array();

    /**
     * Ordina una lista di Model secondo le chiavi-valori passate in "orders"
     * @param array $results lista di modelli
     * @param array $orders campi da ordinare (ex "key1"=>"asc", "key2"=>"desc")
     * @param boolean $flgClass se true "orders" è un array di oggetti ObjectKeyValue @see ObjectKeyValue (di default true)
     * @return array array ordinato
     */
    function sort(&$results, $orders, $flgClass = true) {
        usort($results, function ($a, $b) use ($orders, $flgClass) {
            $c = 0;
            foreach ($orders as $key => $order) {
                if ($flgClass) {
                    $c = $order->value == 'asc' ? strcmp($a[$this->alias][$order->key], $b[$this->alias][$order->key]) : strcmp($b[$this->alias][$order->key], $a[$this->alias][$order->key]);
                } else {
                    $c = $order == 'asc' ? strcmp($a[$this->alias][$key], $b[$this->alias][$key]) : strcmp($b[$this->alias][$key], $a[$this->alias][$key]);
                }
                if ($c != 0) {
                    return $c;
                }
            }
        });

        return $results;
    }

    /**
     * Imposta le date di un Model estratto in formato timezone di sistema in cifre
     * @param type[] $data lista di modelli da impostare
     */
    function setTimezoneDate(&$data) {
        foreach ($data as &$obj) {
            foreach ($this->getColumnTypes() as $key => $value) {
                if ($value === 'datetime' || $value === 'date') {
                    if (!empty($obj[$this->alias][$key])) {
                        $obj[$this->alias][$key] = TimezoneUtility::convertDateTimezoneToServer($obj[$this->alias][$key], Defaults::get("timezone_db"));
                    }
                }
            }
        }
    }

    /**
     * Imposta un array di campi di un Model estratto in modalitÃ  cryptata
     * @param type[] $data lista di modelli da impostare
     * @param array $crypts array di campi da cryptare (es. "dta" => "SHA256" )
     */
    function setFieldEncrypted(&$data, $crypts = array()) {
        foreach ($data as &$obj) {
            if (array_key_exists($this->alias, $obj)) {
                foreach ($crypts as $field => $type) {
                    $obj[$this->alias][$field] = CryptingUtility::encryptByType($obj[$this->alias][$field], $type);
                }
            }
        }
    }

    /**
     * Imposta un array di campi di un Model estratto in modalitÃ  decryptata
     * @param type[] $data lista di modelli da impostare
     * @param array $crypts array di campi da decryptare (es. "dta" => "SHA256" )
     */
    function setFieldDecrypted(&$data, $decrypts = array()) {
        foreach ($data as &$obj) {
            if (array_key_exists($this->alias, $obj)) {
                foreach ($decrypts as $field => $type) {
                    $obj[$this->alias][$field] = CryptingUtility::decryptByType($obj[$this->alias][$field], $type);
                }
            }
        }
    }

    /**
     * Imposta un array di campi di un Model da salvare in modalità  cryptata
     * @param array $toCrypts array di campi da cryptare (es. "dta" => "SHA256" )
     */
    function setFieldToCrypt($toCrypts = array()) {
        if (array_key_exists($this->alias, $this->data)) {
            foreach ($toCrypts as $field => $type) {
                $this->data[$this->alias][$field] = CryptingUtility::encryptByType($this->data[$this->alias][$field], $type);
            }
        }
    }

    /**
     * Traduce il valore del campo "fieldForValue" con la label "TABELLA_VALORE" e
     * lo setta nel campo "fieldDest"
     * @param type[] $data lista di modelli da modificare
     * @param string $fieldForValue campo da cui prendere il valore
     * @param string $fieldDest campo in cui settare la traduzione di quel valore
     * @param string $file nome del file po da cui tradurre la label
     */
    function translateValueInField(&$data, $fieldForValue, $fieldDest, $file) {
        foreach ($data as &$obj) {
            if (array_key_exists($this->alias, $obj) && !empty($obj[$this->alias][$fieldForValue])) {
                //internalization
                $label = strtoupper(str_replace("_fk", "", $this->alias) . "_" . $obj[$this->alias][$fieldForValue]);
                $obj[$this->alias][$fieldDest] = TranslatorUtility::__translate($label, $file);
            }
        }
    }

    function setGroups(&$data) {
        foreach ($data as &$obj) {
            $grouprelation = new Grouprelation();
            $grouprelationList = $grouprelation->find('all', array(
                'conditions' => array(
                    'tableid' => $obj[$this->alias]['id'],
                    'tablename' => $this->useTable,
                ),
            ));
            if (!ArrayUtility::isEmpty($grouprelationList)) {
                $arrCod = array();
                $arrGroupcod = array();
                foreach ($grouprelationList as $grouprelationModel) {
                    array_push($arrCod, $grouprelationModel['Grouprelation']['cod']);
                    array_push($arrGroupcod, $grouprelationModel['Grouprelation']['groupcod']);
                }
                $obj[$this->alias]['grouprelation_cod'] = $arrCod;
                $obj[$this->alias]['grouprelation_groupcod'] = $arrGroupcod;
            }
        }
    }

    // ---------------------- COMMONS INHERITS
    public function afterFind($results, $primary = false) {
        if ($this->flgTimezone) {
            $this->setTimezoneDate($results);
        }
        if ($this->flgEncrypt && !ArrayUtility::isEmpty($this->crypts)) {
            $this->setFieldEncrypted($results, $this->crypts);
        }
        if ($this->flgDecrypt && !ArrayUtility::isEmpty($this->decrypts)) {
            $this->setFieldDecrypted($results, $this->decrypts);
        }
        if ($this->flgAllGroups) {
            $this->setGroups($results);
        }
        if (!ArrayUtility::isEmpty($this->orders)) {
            $results = $this->sort($results, $this->orders);
        }
        if (!empty($this->fileI18n) && !ArrayUtility::isEmpty($this->fieldsI18n)) {
            foreach ($this->fieldsI18n as $field) {
                $this->translateValueInField($results, $field, $field, $this->filei18n);
            }
        }

        return $results;
    }

    public function beforeSave($options = array()) {
        if (!$this->skipBeforeSave) {
            if ($this->saveEncrypt && !ArrayUtility::isEmpty($this->toCrypts)) {
                $this->setFieldToCrypt($this->toCrypts);
            }
            return parent::beforeSave($options);
        }
    }
}