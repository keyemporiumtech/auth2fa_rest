<?php
App::uses("Enables", "Config/system");
App::uses("Codes", "Config/system");
App::uses("LogUtility", "modules/coreutils/utility");
// modules
App::uses('CakeutilsConfig', 'modules/cakeutils/config');
App::uses('EnumQueryLike', 'modules/cakeutils/config');
App::uses('EnumQuerySign', 'modules/cakeutils/config');
App::uses('EnumQueryOperator', 'modules/cakeutils/config');
App::uses('EnumQueryType', 'modules/cakeutils/config');
App::uses("JsonDecoder", "modules/cakeutils/plugin/jsonDecoder");
App::uses("DBCondition", "modules/cakeutils/classes");
App::uses("DBOrder", "modules/cakeutils/classes");
App::uses("DBPaginate", "modules/cakeutils/classes");
// utility
App::uses("ArrayUtility", "modules/coreutils/utility");
App::uses("StringUtility", "modules/coreutils/utility");
App::uses("SystemUtility", "modules/coreutils/utility");
App::uses("DBUtility", "modules/cakeutils/utility");
App::uses("CompleteFields", "modules/cakeutils/utility");

class AppGenericBS {
    public $dao;
    public $json = false;
    public $acceptNull = false;
    public $createdDefault = true; //Quando crea un oggetto setta la data di creazione alla data attuale
    public $params = array();
    public $cnt = 0;
    public $orderDefault = array();
    // controlli
    public $className;
    // log
    public $LOG_CLASS = "AppGenericBS";
    public $LOG_FUNCTION = "";

    public function __construct($class) {
        $this->className = $class;
        $this->LOG_CLASS = $class;
        $this->dao = new $class();
        $this->params['recursive'] = CakeutilsConfig::$MAX_LEVEL_RECURSIVLY;
    }

    public function logSource() {
        return " [CLASS]: " . $this->LOG_CLASS . " [FUNCTION]: " . $this->LOG_FUNCTION . " ";
    }

    public function logDataSource($onlyquery = false) {
        $db = $this->dao->getDataSource();
        if ($onlyquery) {
            $log = array();
            foreach ($db->getLog(false, false)['log'] as $row) {
                array_push($log, $row['query']);
            }
            return $log;
        }
        return $db->getLog(false, false);
    }

    public function logQuery() {
        if (Enables::get("log_query")) {
            LogUtility::write("query", "QUERY", ArrayUtility::toPrintStringNewLine($this->logDataSource(true)));
        }
    }

    /**
     * Assegna un valore ad una proprietà dell'entity dao
     * @param string $key proprietà da modificare
     * @param type $value valore da assegnare
     * @param type $dao entity dao, se non è valorizzata prende il dao corrente (di default null)
     */
    public function addPropertyDao($key, $value, $dao = null) {
        SystemUtility::addValueToProperty(empty($dao) ? $this->dao : $dao, $key, $value);
    }

    /**
     * Resetta le chiavi fields, conditions e orders dell'array "params" e ricrea l'entity dao
     */
    public function reset() {
        $this->dao = new $this->className();
        $this->params['fields'] = array();
        $this->params['conditions'] = array();
        $this->params['order'] = array();
        $this->params['joins'] = array();
    }

    /**
     * Genera un oggetto di tipo "className"
     * @return type oggetto di tipo "className"
     */
    public function instance() {
        $obj = $this->dao->create();
        foreach ($this->dao->getColumnTypes() as $key => $value) {
            if ($value == "string" || $value == "text") {
                $obj[$this->className][$key] = "";
            } elseif ($value == "date") {
                $obj[$this->className][$key] = null;
            } elseif ($value == "float" || $value == "number") {
                $default = $this->dao->schema($key)['default'];
                $obj[$this->className][$key] = $default;
            } elseif ($value == "datetime") {
                $obj[$this->className][$key] = null;
            }
            //data di creazione
            if ($key == 'created' && $this->createdDefault) {
                $obj[$this->className][$key] = date('Y-m-d H:i:s');
            }
        }
        return $obj;
    }

    /**
     * Salva un oggetto nel db
     * @param type $data oggetto da salvare
     * @return string|null id salvato
     * @throws Exception eccezione di salvataggio
     */
    public function save($data) {
        if ($this->dao->save($data)) {
            $this->logQuery();
            return $this->dao->id;
        }
        if (!$this->acceptNull) {
            throw new Exception("Errore nel salvataggio di un oggetto di tipo " . $this->className);
        } else {
            return null;
        }
    }

    /**
     * Aggiorna un campo di un record in tabella
     * @param string $id id del record da aggiornare
     * @param string $field campo da aggiornare
     * @param string $value valore da inserire in "field"
     */
    public function updateField($id, $field, $value) {
        $this->dao->id = $id;
        $this->dao->skipBeforeSave = true;
        $this->dao->saveField($field, $value);
        $this->logQuery();
    }

    /**
     * Cancella un record
     * @param string $id id del record da cancellare
     */
    public function delete($id) {
        $this->dao->delete($id);
        $this->logQuery();
    }

    //-----------------------QUERY
    /**
     * Aggiunge ad una chiave "key", "className" punto "key" se "key" non contiene già il punto
     * @param string $key chiave da valutare
     */
    private function evaluateKeyForCondition(&$key) {
        if (strpos($key, ".") == false) {
            $key = $this->className . "." . $key;
        }
    }

    /**
     * Ritorna una lista secondo i parametri "params" impostati
     * @return type[] lista secondo i parametri "params" impostati
     */
    public function all() {
        $this->evalConditions();
        $list = $this->dao->find('all', $this->params);
        $this->logQuery();
        return $this->compactList($list);
    }

    /**
     * Ritorna un singolo record o per id o secondo i parametri "params" impostati
     * @param string $id id del record (di default null)
     * @return type un singolo record o per id o secondo i parametri "params" impostati
     */
    public function unique($id = null) {
        $this->evalConditions();
        if (!empty($id)) {
            $this->params['conditions'][$this->className . '.id'] = $id;
        }
        $list = $this->dao->find('first', $this->params);
        $this->logQuery();
        return $this->compactUnique($list);
    }

    /**
     * Ritorna il numero di record secondo i parametri "params" impostati
     * @return int il numero di record secondo i parametri "params" impostati
     */
    public function count() {
        $this->evalConditions();
        $num = $this->dao->find('count', $this->params);
        $this->logQuery();
        return $num;
    }

    /**
     * Ritorna il numero totale di elementi nel caso di "params" con limit, offset o page valorizzati.<br/>
     * Usato per tornare il numero totale di elementi presenti in liste paginate
     * @return int il numero totale di elementi nel caso di "params" con limit, offset o page valorizzati
     */
    public function getCountForPaginate() {
        if (!empty($this->params['limit']) || !empty($this->params['offset']) || !empty($this->params['page'])) {
            $copyParam = $this->params;
            $copyParam['limit'] = null;
            $copyParam['offset'] = null;
            $copyParam['page'] = null;
            $this->dao->flgAllGroups = false;
            $num = $this->dao->find('count', $copyParam);
            $this->logQuery();
            return $num;
        }
    }

    /**
     * Ritorna il valore di una query count
     * @param string $sql query count
     * @param string $field alias del count
     * @return int il valore di una query count
     */
    public function queryCount($sql, $field) {
        //$this->className = $this->dao->useTable;
        $result = $this->dao->query($sql);
        $this->logQuery();
        return !empty($result[0][0][$field]) ? $result[0][0][$field] : 0;
    }

    /**
     * Esegue una query generica e ritorna un singolo record se "evalUnique" è true, una lista altrimenti
     * @param string $sql query
     * @param boolean $evalUnique se true indica che bisogna tornare un solo record (di default "true")
     * @return type|type[] un singolo record se "evalUnique" è true, una lista altrimenti
     */
    public function query($sql, $evalUnique = true) {
        //$this->className = $this->dao->useTable;
        $this->evalSqlQuery($sql);
        $this->evalSqlConditions($sql);
        $objects = $this->dao->query($sql);
        $this->logQuery();
        if (count($objects) == 1 && $evalUnique) {
            return $this->compactUnique($objects[0]);
        }
        return $this->compactList($this->convertQueryList($objects));
    }

    /**
     * Esegue una query di update dati
     * @param string $sql query
     */
    public function execute($sql) {
        //$this->className = $this->dao->useTable;
        $this->dao->query($sql);
        $this->logQuery();
    }

    /**
     * Esegue una query generica e ritorna il risultato della query invocata sull'oggetto "dao" entity
     * @param string $sql query
     * @return risultato della query invocata sull'oggetto "dao" entity
     */
    public function genericQuery($sql) {
        //$this->className = $this->dao->useTable;
        $result = $this->dao->query($sql);
        $this->logQuery();
        return $result;
    }

    /**
     * Ritorna una lista paginata a seconda delle "conditions" (@see DBCondition), "orders" (@see DBOrder) e "paginate" (@see DBPaginate)
     * @param array|type[] $conditions array di condizioni @see DBCondition
     * @param array|type[] $orders array di ordinamenti @see DBOrder
     * @param type $paginate informazioni di paginazione @see DBPaginate
     * @return type[] una lista paginata a seconda delle "conditions" (@see DBCondition), "orders" (@see DBOrder) e "paginate" (@see DBPaginate)
     */
    public function table($conditions = null, $orders = null, $paginate = null) {
        ($this->json) ? $this->applyDBPaginateByJson($paginate) : $this->applyDBPaginate($paginate);
        ($this->json) ? $this->applyDBConditionByJsonMulti($conditions) : $this->applyListDBCondition($conditions);
        ($this->json) ? $this->applyDBOrderByJsonMulti($orders) : $this->applyListDBOrder($orders);
        $list = $this->all();
        $this->logQuery();
        return $list;
    }

    /**
     * Ritorna una struttura cakePhp ad albero secondo i parametri "params" impostati.<br/>
     * Imposta i campi "numChildren", "hasChildren" e "children" per gli oggetti ritornati.
     * @param string $parentId id del ramo da cui partire, se è null dalla radice (di default null)
     * @param string $parentField nome del campo che identifica il ramo padre
     * @param boolean $async se è true ritorna solo il numero di figli di un ramo ("numChildren" e "hasChildren") con "children" null, altrimenti ritorna ricorsivamente anche gli array dei figli in "children" (di default false)
     * @return type[] una struttura cakePhp ad albero secondo i parametri "params" impostati
     */
    public function tree($parentId = null, $parentField = 'parent_id', $async = false) {
        $this->evalConditions();
        $list = array();
        if (!empty($parentId)) {
            $this->params['conditions'][$this->className . '.id'] = $parentId;
            $initElement = $this->dao->find('first', $this->params);
            if (!empty($initElement)) {
                array_push($list, $initElement);
            }
            // $this->hyerarchly($initElement [$this->className] ['id'], $parentField, $all, $initElement);
        } else {
            // $this->params ['conditions'] [$this->className . "." . $parentField]=0;
            $list = $this->dao->find('all', $this->params);
        }
        $this->logQuery();
        $this->buildTree($list, $parentField, $async);
        return $this->compactList($list);
    }

    private function buildTree(&$elements = array(), $parentField = 'parent_id', $async = false) {
        if (!array_key_exists('conditions', $this->params)) {
            $this->params['conditions'] = array();
        }
        if (array_key_exists($this->className . ".id", $this->params['conditions'])) {
            unset($this->params['conditions'][$this->className . ".id"]);
        }
        $i = 0;
        foreach ($elements as $element) {
            $this->params['conditions'][$this->className . "." . $parentField] = $element[$this->className]['id'];
            if (!$async) {
                $children = $this->dao->find('all', $this->params);
                $elements[$i][$this->className]['numChildren'] = count($children);
                $elements[$i][$this->className]['hasChildren'] = count($children) > 0 ? true : false;
                $elements[$i][$this->className]['children'] = ArrayUtility::isEmpty($children) ? null : $children;
                $this->buildTree($elements[$i][$this->className]['children'], $parentField, $async);
            } else {
                $num_children = $this->count('all', $this->params);
                $elements[$i][$this->className]['numChildren'] = $num_children;
                $elements[$i][$this->className]['hasChildren'] = $num_children > 0 ? true : false;
                $elements[$i][$this->className]['children'] = null;
            }
            $i++;
        }
        $this->logQuery();
    }

    //----------------QUERY UTILITY
    /**
     * Imposta in "params" la chiave "lock" a true
     */
    public function setLock() {
        $this->params['lock'] = true;
    }

    /**
     * Imposta in "params" il valore delle chiavi "limit" e "page"
     * @param int $limit numero di righe
     * @param int $page pagina richiesta
     */
    public function setPaginate($limit, $page = 1) {
        $this->params['limit'] = $limit;
        $this->params['page'] = $page;
    }

    /**
     * Imposta in "params" il valore delle chiavi "limit" e "offset"
     * @param int $limit numero di righe
     * @param int $offset offset richiesto
     */
    public function setLimitOffset($limit, $offset = 0) {
        $this->params['limit'] = $limit;
        if ($offset > 0) {
            $this->params['offset'] = $offset;
        }
    }

    /**
     * Aggiunge in "params" un array di campi "fields"
     * @param string[] $fields campi da richiedere in query
     * @param boolean $reset se true resetta "fields" (di default false)
     */
    public function addFields($fields, $reset = false) {
        if ($reset) {
            $this->params['fields'] = array();
        }
        $this->params['fields'] = $fields;
    }

    /**
     * Aggiunge in "params" una condition chiave-valore
     * @param string $key chiave della condition
     * @param string $value valore della condition
     * @param boolean $reset se true resetta "conditions" (di default false)
     */
    public function addCondition($key, $value, $reset = false) {
        if ($reset) {
            $this->params['conditions'] = array();
        }
        $this->params['conditions'][$key] = $value;
    }

    /**
     * Aggiunge in "params" una contain chiave-valore
     * @param string $key chiave della contain
     * @param string $value valore della contain
     * @param boolean $reset se true resetta "contain" (di default false)
     */
    public function addContain($key, $value, $reset = false) {
        if ($reset) {
            $this->params['contain'] = array();
        }
        $this->params['contain'][$key] = $value;
    }

    /**
     * Aggiunge in "params" un order chiave-orientamento
     * @param string $key chiave dell'order
     * @param string $orientation orientamento (di default ASC)
     * @param boolean $reset se true resetta "order" (di default false)
     */
    public function addOrder($key, $orientation = 'ASC', $reset = false) {
        if ($reset) {
            $this->params['order'] = array();
        }
        $this->params['order'][$key] = $orientation;
    }

    /**
     * Aggiunge una condition di tipo LIKE
     * @param string $key chiave del LIKE
     * @param string $value valore del LIKE
     * @param type $type tipo di LIKE @see EnumQueryLike (di default PRECISION)
     * @param boolean $reset se true resetta "conditions" (di default false)
     */
    public function addLike($key, $value, $type = EnumQueryLike::PRECISION, $reset = false) {
        if ($reset) {
            $this->params['conditions'] = array();
        }
        $obj = DBUtility::getLikeCondition($key, $value, $type);
        $this->params['conditions'][$obj->key] = $obj->value;
    }

    /**
     * Aggiunge una condition con operatore
     * @param string $key chiave dell'operatore
     * @param string $value valore dell'operatore
     * @param type $sign tipo di operatore
     * @param boolean $reset se true resetta "conditions" (di default false)
     */
    public function addSign($key, $value, $sign = EnumQuerySign::NOTHING, $reset = false) {
        if ($reset) {
            $this->params['conditions'] = array();
        }
        $obj = DBUtility::getSignCondition($key, $value, $sign);
        $this->params['conditions'][$obj->key] = $obj->value;
    }

    /**
     * Aggiunge una condition di tipo BETWEEN
     * @param string $key chiave della BETWEEN
     * @param string $start valori iniziale della BETWEEN
     * @param string $end valore finale della BETWEEN
     * @param boolean $reset se true resetta "conditions" (di default false)
     */
    public function addBetween($key, $start, $end, $reset = false) {
        if ($reset) {
            $this->params['conditions'] = array();
        }
        $obj = DBUtility::getBetweenCondition($key, $start, $end);
        $this->params['conditions'][$obj->key] = $obj->value;
    }

    /**
     * Aggiunge una condition di tipo NOT
     * @param string $key chiave del NOT
     * @param string $value valore in NOT
     * @param boolean $reset se true resetta "conditions" (di default false)
     */
    public function addSimpleNOT($key, $value, $reset = false) {
        if ($reset) {
            $this->params['conditions'] = array();
        }
        $this->params['conditions']['NOT'] = array(
            $key => $value,
        );
    }

    /**
     * Aggiunge un array di condizioni in AND
     * @param array $conditions array chiave-valore di condizioni
     * @param string $reset se true resetta "conditions" (di default false)
     */
    public function addArrayAnd($conditions = array(), $reset = false) {
        if ($reset) {
            $this->params['conditions'] = array();
        }
        $this->params['conditions']['AND'] = $conditions;
    }

    /**
     * Aggiunge un array di condizioni in OR
     * @param array $conditions array chiave-valore di condizioni
     * @param string $reset se true resetta "conditions" (di default false)
     */
    public function addArrayOr($conditions = array(), $reset = false) {
        if ($reset) {
            $this->params['conditions'] = array();
        }
        $this->params['conditions']['OR'] = $conditions;
    }

    /**
     * Aggiunge un array di condizioni in NOT
     * @param array $conditions array chiave-valore di condizioni
     * @param string $reset se true resetta "conditions" (di default false)
     */
    public function addArrayNot($conditions = array(), $reset = false) {
        if ($reset) {
            $this->params['conditions'] = array();
        }
        $this->params['conditions']['NOT'] = $conditions;
    }

    /**
     * Aggiunge in "params" una condition di join
     * @param string $table nome tabella del database
     * @param string $alias alias da assegnare alla tabella
     * @param string $type tipo di join ("INNER", "LEFT" ...)
     * @param array $conditions condizione di join
     */
    public function addJoin($table, $alias, $type, $conditions = array()) {
        if (!array_key_exists("joins", $this->params)) {
            $this->params['joins'] = array();
        }
        $join = array(
            "table" => $table,
            "alias" => $alias,
            "type" => $type,
            "conditions" => $conditions,
        );
        array_push($this->params['joins'], $join);
    }

    //----------------APPLY OBJECTS
    //--------- DBPaginate PAGINATE
    /**
     * Applica una paginazione da oggetto DBPaginate @see DBPaginate
     * @param type $paginate oggetto DBPaginate @see DBPaginate
     */
    public function applyDBPaginate($paginate) {
        if (!empty($paginate) && !empty($paginate->limit)) {
            $this->setPaginate($paginate->limit, $paginate->page);
        }
    }

    /**
     * Applica una paginazione da oggetto DBPaginate @see DBPaginate codificato in json.<br/>
     * Usa il plugin jsonDecoder @see JsonDecoder
     * @param type $jsonPaginate oggetto DBPaginate @see DBPaginate codificato in json
     */
    public function applyDBPaginateByJson($jsonPaginate) {
        if (!empty($jsonPaginate)) {
            $jsonDecoder = new JsonDecoder();
            $obj = $jsonDecoder->decode($jsonPaginate, DBPaginate::class);
            $this->applyDBPaginate($obj);
        }
    }

    //--------- DBOrder ORDERS
    /**
     * Applica l'ordinamento di default definito in "orderDefault"
     */
    public function applyDefaultOrder() {
        foreach ($this->orderDefault as $key => $value) {
            $this->evaluateKeyForCondition($key);
            $this->addOrder($key, $value);
        }
    }

    /**
     * Applica un ordinamento da oggetto DBOrder @see DBOrder, o ordinamento di default se "order" è null
     * @param type $order oggetto DBOrder @see DBOrder
     */
    public function applyDBOrder($order) {
        if (empty($order)) {
            $this->applyDefaultOrder();
        } else {
            $this->evaluateKeyForCondition($order->key);
            $this->addOrder($order->key, $order->value);
        }
    }

    /**
     * Applica una lista di ordinamenti in array di oggetti DBOrder @see DBOrder, o ordinamento di default se "list" è vuoto
     * @param type[] $list array di oggetti DBOrder @see DBOrder
     */
    public function applyListDBOrder($list = array()) {
        if (ArrayUtility::isEmpty($list)) {
            $this->applyDefaultOrder();
        } else {
            foreach ($list as $order) {
                $this->applyDBOrder($order);
            }
        }
    }

    /**
     * Applica un ordinamento DBOrder @see DBOrder codificato in json, o ordinamento di default se "list" è vuoto.<br/>
     * Usa il plugin jsonDecoder @see JsonDecoder
     * @param type $jsonOrder oggetto DBOrder @see DBOrder codificato in json
     */
    public function applyDBOrderByJson($jsonOrder) {
        if (empty($jsonOrder)) {
            $this->applyDefaultOrder();
        } else {
            $jsonDecoder = new JsonDecoder();
            $obj = $jsonDecoder->decode($jsonOrder, DBOrder::class);
            $this->applyDBOrder($obj);
        }
    }

    /**
     * Applica una lista di ordinamenti a partire da array di DBOrder @see DBOrder codificato in json, o ordinamento di default se "list" è vuoto.<br/>
     * Usa il plugin jsonDecoder @see JsonDecoder
     * @param type $jsonOrders array json di ordinamenti DBOrder @see DBOrder
     */
    public function applyDBOrderByJsonMulti($jsonOrders) {
        if (empty($jsonOrders)) {
            $this->applyDefaultOrder();
        } else {
            $jsonDecoder = new JsonDecoder();
            $list = $jsonDecoder->decodeMultiple($jsonOrders, DBOrder::class);
            $this->applyListDBOrder($list);
        }
    }

    //--------- DBConditions FILTER
    /**
     * Applica una condition a partire da un oggetto DBCondition @see DBCondition
     * @param type $obj oggetto DBCondition @see DBCondition
     */
    public function applyDBCondition($obj) {
        $this->evaluateKeyForCondition($obj->key);
        if (!empty($obj->operator)) {
            $obj = DBUtility::buildOperatorByCondition($obj->operator, $obj->children);
            $this->params['conditions'][$obj->key] = $obj->value;
        } elseif (!empty($obj->sign)) {
            $this->addSign($obj->key, $obj->value, $obj->sign);
        } elseif (!empty($obj->like)) {
            $this->addLike($obj->key, $obj->value, $obj->like);
        } elseif (!empty($obj->between)) {
            $this->addBetween($obj->key, $obj->between[0], $obj->between[1]);
        } else {
            $this->addCondition($obj->key, $obj->value);
        }
    }

    /**
     * Applica una lista di conditions a partire da un array di oggetti DBCondition @see DBCondition
     * @param type[] $list array di oggetti DBCondition @see DBCondition
     */
    public function applyListDBCondition($list = array()) {
        if (!ArrayUtility::isEmpty($list)) {
            foreach ($list as $condition) {
                $this->applyDBCondition($condition);
            }
        }
    }

    /**
     * Applica una condition DBCondition @see DBCondition codificato in json.<br/>
     * Usa il plugin jsonDecoder @see JsonDecoder
     * @param type $jsonCondition oggetto DBCondition @see DBCondition codificato in json
     */
    public function applyDBConditionByJson($jsonCondition) {
        $jsonDecoder = new JsonDecoder();
        $obj = $jsonDecoder->decode($jsonCondition, DBCondition::class);
        $this->applyDBCondition($obj);
    }

    /**
     * Applica una lista di conditions a partire da array di DBCondition @see DBCondition codificato in json.<br/>
     * Usa il plugin jsonDecoder @see JsonDecoder
     * @param type $jsonConditions array json di conditions DBCondition @see DBCondition
     */
    public function applyDBConditionByJsonMulti($jsonConditions) {
        $jsonDecoder = new JsonDecoder();
        $list = $jsonDecoder->decodeMultiple($jsonConditions, DBCondition::class);
        $this->applyListDBCondition($list);
    }

    //----------------RELATIONS FK
    /**
     * Aggiunge ad un dao "object" il belongsTo prima di una query, tra quelli definiti in "arrayBelongsTo" del modello
     * @param string $name nome della chiave di "arrayBelongsTo"
     * @param type $dao oggetto dao entity. Se non passato è quello corrente (di default null)
     * @param array $options opzioni di filtro
     */
    public function addBelongsTo($name, $dao = null, $options = array()) {
        $object = empty($dao) ? $this->dao : $dao;
        if (array_key_exists($name, $object->arrayBelongsTo)) {
            $object->belongsTo[$name] = $object->arrayBelongsTo[$name];
            if (!empty($options)) {
                foreach ($options as $key => $value) {
                    $object->belongsTo[$name][$key] = $value;
                }
            }
        }
    }

    /**
     * Aggiunge ad un dao "object" il hasOne prima di una query, tra quelli definiti in "arrayHasOne" del modello
     * @param string $name nome della chiave di "arrayHasOne"
     * @param type $dao oggetto dao entity. Se non passato è quello corrente (di default null)
     * @param array $options opzioni di filtro
     */
    public function addHasOne($name, $dao = null, $options = array()) {
        $object = empty($dao) ? $this->dao : $dao;
        if (array_key_exists($name, $object->arrayHasOne)) {
            $object->hasOne[$name] = $object->arrayHasOne[$name];
            if (!empty($options)) {
                foreach ($options as $key => $value) {
                    $object->hasOne[$name][$key] = $value;
                }
            }
        }
    }

    /**
     * Aggiunge ad un dao "object" il hasMany prima di una query, tra quelli definiti in "arrayHasMany" del modello
     * @param string $name nome della chiave di "arrayHasMany"
     * @param type $dao oggetto dao entity. Se non passato è quello corrente (di default null)
     * @param array $options opzioni di filtro
     */
    public function addHasMany($name, $dao = null, $options = array()) {
        $object = empty($dao) ? $this->dao : $dao;
        if (array_key_exists($name, $object->arrayHasMany)) {
            $object->hasMany[$name] = $object->arrayHasMany[$name];
            if (!empty($options)) {
                foreach ($options as $key => $value) {
                    $object->hasMany[$name][$key] = $value;
                }
            }
        }
    }

    /**
     * Aggiunge ad un dao "object" il virtualFields prima di una query, tra quelli definiti in "arrayVirtualFields" del modello
     * @param string $name nome della chiave di "arrayVirtualFields"
     * @param type $dao oggetto dao entity. Se non passato è quello corrente (di default null)
     */
    public function addVirtualField($name, $dao = null) {
        $object = empty($dao) ? $this->dao : $dao;
        if (array_key_exists($name, $object->arrayVirtualFields)) {
            $query = $object->arrayVirtualFields[$name];
            if ($object->name != $object->alias) {
                $query = str_replace($object->name . '.', $object->alias . '.', $query);
            }
            $object->virtualFields[$name] = $query;
        }
    }

    //----------------COMPACTING
    /**
     * Compatta gli oggetti con campo children di una lista ad albero
     * @param type[] $list array di oggetti da popolare
     * @return type[] oggetti con children valorizzato
     */
    private function prepareChildren($list) {
        $arrayRet = array();
        foreach ($list as $obj) {
            if (array_key_exists("children", $obj[$this->className])) {
                $obj[$this->className]["children"] = !ArrayUtility::isEmpty($obj[$this->className]["children"]) ? $this->prepareChildren($obj[$this->className]["children"]) : null;
            }
            $this->evalObjectObtained($obj);
            array_push($arrayRet, $obj[$this->className]);
        }
        return $arrayRet;
    }

    /**
     * Compatta gli oggetti con campo children di una lista ad albero e valuta se ritornarli in formato json
     * @param type[] $list array di oggetti da popolare
     * @return type[] oggetti con children valorizzato
     */
    private function compactList($list) {
        $arrayRet = array();
        foreach ($list as $obj) {
            if (array_key_exists("children", $obj[$this->className])) {
                $obj[$this->className]["children"] = !ArrayUtility::isEmpty($obj[$this->className]["children"]) ? $this->prepareChildren($obj[$this->className]["children"]) : null;
            }
            $this->evalObjectObtained($obj);
            array_push($arrayRet, $this->json ? $obj[$this->className] : $obj);
        }
        return $this->json ? json_encode($arrayRet) : $arrayRet;
    }

    /**
     * Compatta un singolo oggetto e valuta se ritornarlo in formato json
     * @param mixed|array $obj oggetto da compattare
     * @return mixed oggetto compattato
     */
    private function compactUnique($obj) {
        $this->evalObjectObtained($obj);
        return ($this->json) ? (array_key_exists($this->className, $obj) ? json_encode($obj[$this->className]) : "") : $obj;
    }

    //----------------COMPACTING
    /**
     * Aggiunge ad una query "sql" la WHERE 1=1 se "sql" non contiene clausole where
     * @param string $sql query
     */
    public function evalSqlQuery(&$sql) {
        if (strpos(strtolower($sql), 'where') == false) {
            $sql .= " WHERE 1=1";
        }
    }

    /**
     * Aggiunge ad un oggetto i campi di foreign keys e virtual fields
     * @param type $obj oggetto da integrare
     */
    public function evalObjectObtained(&$obj) {
        if (!$this->acceptNull) {
            $this->manageExceptionObj($obj);
        }
        $this->evalFk($obj);
        $this->evalVf($obj);
    }

    /**
     * Lancia un'eccezione se "obj" è nullo o non contiene come chiave il "className"
     * @param type $obj oggetto da valutare
     * @throws Exception eccezione oggetto non trovato o classe non trovata
     */
    private function manageExceptionObj($obj) {
        if (empty($obj)) {
            /** @var string */
            $translated = __d("appgenericbs", "ERROR_OBJECT_NOT_FOUND", array(
                $this->className,
            ));
            throw new Exception(mb_convert_encoding($translated, 'UTF-8'), Codes::get("EXCEPTION_GENERIC"));
        }
        if (empty($obj[$this->className])) {
            /** @var string */
            $translated = __d("appgenericbs", "ERROR_OBJECT_CLASS_NOT_FOUND", array(
                $this->className,
            ));
            throw new Exception(mb_convert_encoding($translated, 'UTF-8'), Codes::get("EXCEPTION_GENERIC"));
        }
    }

    /**
     * Aggiunge ad un oggetto le foreign key definite in belongsTo
     * @param type $obj oggetto da modificare
     */
    private function evalFk(&$obj) {
        foreach ($this->dao->belongsTo as $key => $fk) {
            if (!empty($obj[$key])) {
                $obj[$this->className][$key] = $obj[$key];
            }
        }
    }

    /**
     * Valuta l'esistenza di virtual fields e li aggiunge all'oggetto
     * @param type $obj oggetto da valutare
     */
    private function evalVf(&$obj) {
        foreach ($obj as $key => $value) {
            if ($key !== $this->className && is_array($value)) {
                foreach ($value as $name => $vf) {
                    if (StringUtility::contains($name, $this->className . "__")) {
                        $propName = str_replace($this->className . "__", "", $name);
                        $obj[$this->className][$propName] = $vf;
                    }
                }
            }
        }
    }

    /**
     * Valuta se ci sono le condizioni di virtual field in "conditions" prima di una query
     */
    public function evalConditions() {

        //TO IMPLEMENT IN SUPERCLASS FOR SPECIFIC CLASS CONDITION
        if (array_key_exists("conditions", $this->params)) {
            foreach ($this->params['conditions'] as $key => $value) {
                if (StringUtility::contains($key, "__")) {
                    unset($this->params['conditions'][$key]);
                    $paramName = str_replace($this->className . ".", "", $key);
                    CompleteFields::evalConditionVirtualfields($this, $paramName, $value, $this->params['conditions']);
                }
            }
        }

        if (array_key_exists("order", $this->params)) {
            foreach ($this->params['order'] as $key => $value) {
                if (StringUtility::contains($key, "__")) {
                    unset($this->params['order'][$key]);
                    $paramName = str_replace($this->className . ".", "", $key);
                    CompleteFields::evalOrderVirtualfields($this, $paramName, $value, $this->params['order']);
                }
            }
        }
    }

    /**
     * Metodo da implementare in oggetti business per specifiche valutazioni di query prima della chiamata al db
     * @param string $sql query
     */
    public function evalSqlConditions(&$sql) {
        //TO IMPLEMENT IN SUPERCLASS FOR SPECIFIC CLASS CONDITION
        //Used first every query
    }

    //----------------CONVERTING
    /**
     * Converte una lista di oggetti
     * @param type[] $list lista di oggetti da convertire
     * @return lista di oggetti convertita
     */
    private function convertQueryList($list) {
        $arrayRet = array();
        foreach ($list as $el) {
            array_push($arrayRet, $el);
        }
        return $arrayRet;
    }

    /**
     * Ritorna il json di un oggetto senza la chiave "className"
     * @param mixed $obj oggetto
     * @return mixed json dell'oggetto senza la chiave "className"
     */
    public function getJson($obj) {
        return json_encode($obj[$this->className]);
    }

    /**
     * Ritorna un oggetto entity di tipo "className" a partire da un json
     * @param string $json json
     * @return mixed oggetto entity
     */
    public function getObj($json) {
        $obj = array(
            $this->className => json_decode($json),
        );
        return $obj;
    }

    //----------------GROUPS

    public function sqlConditionGroups(&$sql, $groups = null, $likegroups = null, $json = false) {
        if ($json) {
            $groups = !empty($groups) ? json_decode($groups, true) : array();
        } else {
            $groups = !ArrayUtility::isEmpty($groups) ? $groups : array();
        }
        if (!ArrayUtility::isEmpty($groups)) {
            $sql .= " AND id IN (SELECT tableid FROM grouprelations WHERE tablename='{$this->dao->useTable}'";

            if (empty($likegroups) || $likegroups == EnumQueryLike::PRECISION) {
                $sql .= " AND groupcod IN (" . ArrayUtility::getStringByList($groups, false, ",", "'") . ")";
            } else {
                foreach ($groups as $group) {
                    $like = "";
                    switch ($likegroups) {
                    case EnumQueryLike::LEFT:
                        $like = "'%$group'";
                        break;
                    case EnumQueryLike::RIGHT:
                        $like = "'$group%'";
                        break;
                    case EnumQueryLike::LEFT_RIGHT:
                        $like = "'%$group%'";
                        break;
                    }
                    if (!empty($like)) {
                        $sql .= " AND groupcod LIKE {$like})";
                    } else {
                        $sql .= " AND groupcod IN (" . ArrayUtility::getStringByList($groups, false, ",", "'") . ")";
                    }
                }
            }
        }

    }
}
