<?php

App::uses("Enables", "Config/system");
App::uses("Codes", "Config/system");
App::uses("ConnectionManager", "Model");
// utility
App::uses("LogUtility", "modules/coreutils/utility");
App::uses("TranslatorUtility", "modules/cakeutils/utility");
App::uses("ArrayUtility", "modules/coreutils/utility");
App::uses("CompleteFields", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("FileUtility", "modules/coreutils/utility");
// plugin
App::uses("JsonDecoder", "modules/cakeutils/plugin/jsonDecoder");
// objects
App::uses("EnumMessageStatus", "modules/cakeutils/config");
App::uses("EnumMessageType", "modules/cakeutils/config");
App::uses("ObjCodMessage", "modules/cakeutils/classes");
App::uses("DBCondition", "modules/cakeutils/classes");
App::uses("DBOrder", "modules/cakeutils/classes");
App::uses("DBPaginate", "modules/cakeutils/classes");
App::uses("MessageStatus", "modules/cakeutils/classes");
// business
App::uses("GroupBS", "modules/cakeutils/business");
App::uses("GrouprelationBS", "modules/cakeutils/business");

class AppGenericUI {
    /**
     * Oggetto di tipo @see MessageStatus
     * @var Object tipo @see MessageStatus
     */
    public $status = null;
    public $json = null;
    public $localefile = null;
    public $obj = null;
    //paginate
    public $count = 0; // numero totale di record
    public $pages = 0; // numero totale di pagine
    // filtri di foreign key e virtual fields
    /** @var array|string */
    public $belongs = array();
    /** @var array|string */
    public $virtualfields = array();
    /** @var array|string */
    public $flags = array();
    /** @var array|string */
    public $properties = array();
    // JOIN GROUPS
    /** @var array|string */
    public $groups = array();
    public $likegroups = null;
    /** @var array|string */
    public $groupssave = array();
    /** @var array|string */
    public $groupsdel = array();
    // log
    public $LOG_CLASS = "AppGenericUI";
    public $LOG_FUNCTION = "";
    // db
    public $datasource = null;
    public $datasourceName = "default";
    public $transactional = false;

    function __construct($class) {
        $this->LOG_CLASS = $class;
        $this->datasource = ConnectionManager::getDataSource($this->datasourceName);
    }

    /**
     * Mappa l'esito di una chiamata ad un delegate diverso da quello corrente
     * @param type $ui oggetto delegate
     */
    function mappingDelegate($ui, $transactional = false) {
        $this->status = $ui->status;
        $this->count = $ui->count;
        $this->pages = $ui->pages;
        $this->transactional = $transactional;
    }

    /**
     * Assegna i parametri comuni del delegate corrente ad un altro delegate
     * @param type $ui delegate che riceve i parametri
     */
    function assignToDelegate(&$ui) {
        $ui->belongs = $this->belongs;
        $ui->virtualfields = $this->virtualfields;
        $ui->flags = $this->flags;
        $ui->properties = $this->properties;
        $ui->groups = $this->groups;
        $ui->likegroups = $this->likegroups;
        $ui->groupssave = $this->groupssave;
        $ui->groupsdel = $this->groupsdel;
    }

    /**
     * ritorna true se il delegate ha lo status di errore
     * @return boolean true se il delegate ha lo status di errore
     */
    function hasStatusError() {
        if ($this->status && $this->status->statuscod == EnumMessageStatus::ERROR) {
            return true;
        }
        return false;
    }

    function logSource() {
        return MessageUtility::logSource($this->LOG_CLASS, $this->LOG_FUNCTION);
    }

    /**
     * Crea un messaggio da gestire in response (@see MessageStatus)
     * @param type $type tipo di messaggio (@see EnumMessageType)
     * @param string $message messaggio da mandare
     * @param string|Exception $exception eccezione generata
     * @param string $internal messaggio applicativo interno
     * @param string|number $message_cod codice del messaggio principale (se non fornito viene letto da applicationcodes.json)
     * @param string|number $exception_cod codice dell'eccezione (se non fornito viene letto da applicationcodes.json)
     * @param string|number $internal_cod codice del messaggio interno (se non fornito viene letto da applicationcodes.json)
     * @param string|number $response_cod codice della response (utile nei casi di response 200 anche in caso di errore)
     */
    private function manageMessage($type, $message = "", $exception = null, $internal = "", $message_cod = null, $exception_cod = null, $internal_cod = null, $response_cod = null) {
        // MESSAGGIO PUBBLICO
        $objMessage = new ObjCodMessage((empty($message_cod) ? Codes::get("INFO_GENERIC") : $message_cod), $message, $type);

        // ECCEZIONE
        if (empty($exception)) {
            $exception = new Exception("NOT EXCEPTION MANAGED", (empty($exception_cod) ? Codes::get("EXCEPTION_GENERIC") : $exception_cod));
        } elseif (is_string($exception)) {
            $tmp = $exception;
            $exception = new Exception($tmp, (empty($exception_cod) ? Codes::get("EXCEPTION_GENERIC") : $exception_cod));
        }
        $objException = new ObjCodMessage($exception->getCode(), $exception->getMessage());

        // LOGGING
        $objInternal = new ObjCodMessage((empty($internal_cod) ? Codes::get("INTERNAL_GENERIC") : $internal_cod), $internal);

        $this->status = new MessageStatus(EnumMessageStatus::ERROR, $objMessage, $objException, $objInternal, $response_cod);
        MessageUtility::logMessageByMEI($objMessage, $objException, $objInternal, "log_delegate", "delegate", $this->logSource(), $response_cod);
    }

    function ok($message = "", $message_cod = null) {
        $objMessage = new ObjCodMessage((empty($message_cod) ? Codes::get("INFO_GENERIC") : $message_cod), $message, EnumMessageType::INFO);
        $this->status = new MessageStatus(EnumMessageStatus::OK, $objMessage);
    }

    function error($message = "", $exception = null, $internal = "", $message_cod = null, $exception_cod = null, $internal_cod = null, $response_cod = null) {
        $this->manageMessage(EnumMessageType::ERROR, $message, $exception, $internal, $message_cod, $exception_cod, $internal_cod, $response_cod);
    }

    function warning($message = "", $exception = null, $internal = "", $message_cod = null, $exception_cod = null, $internal_cod = null, $response_cod = null) {
        $this->manageMessage(EnumMessageType::WARNING, $message, $exception, $internal, $message_cod, $exception_cod, $internal_cod, $response_cod);
    }

    function fatal($message = "", $exception = null, $internal = "", $message_cod = null, $exception_cod = null, $internal_cod = null, $response_cod = null) {
        $this->manageMessage(EnumMessageType::EXCEPTION, $message, $exception, $internal, $message_cod, $exception_cod, $internal_cod, $response_cod);
    }

    function info($message = "", $exception = null) {
        $this->manageMessage(EnumMessageType::INFO, $message, $exception, "", null, null, Codes::get("INFO_GENERIC"));
    }

    //---------------BUSINESS
    /**
     * Applica ad un business i belongs e i virtualfields settati in formato json nelle variabili belongs, virtualfields e flags del delegate
     * @param AppGenericBS $bs oggetto di business
     */
    function completeByJsonFkVf(AppGenericBS $bs) {
        if ($this->json) {
            $belongs = !empty($this->belongs) ? json_decode($this->belongs, true) : array();
            $virtuals = !empty($this->virtualfields) ? json_decode($this->virtualfields, true) : array();
            $flags = !empty($this->flags) ? json_decode($this->flags, true) : array();
            $properties = !empty($this->properties) ? json_decode($this->properties, true) : array();
            $groups = !empty($this->groups) ? json_decode($this->groups, true) : array();
        } else {
            $belongs = !ArrayUtility::isEmpty($this->belongs) ? $this->belongs : array();
            $virtuals = !ArrayUtility::isEmpty($this->virtualfields) ? $this->virtualfields : array();
            $flags = !ArrayUtility::isEmpty($this->flags) ? $this->flags : array();
            $properties = !ArrayUtility::isEmpty($this->properties) ? $this->properties : array();
            $groups = !ArrayUtility::isEmpty($this->groups) ? $this->groups : array();
        }
        CompleteFields::complete($bs, $belongs, $virtuals, $flags, $properties, $groups, $this->likegroups);
    }

    /**
     * salva un'entità in un gruppo o in un insieme di gruppi
     *
     * @param  type $bs Oggetto di business
     * @param  mixed $idSave id dell'entity
     * @param  boolean $removeAll se true indica di cancellare l'entity da tutti i gruppi
     * @return void
     */
    function saveInGroup(AppGenericBS $bs, $idSave, $removeAll = false) {
        if ($this->json) {
            $groups = !empty($this->groupssave) ? json_decode($this->groupssave, true) : array();
        } else {
            $groups = !ArrayUtility::isEmpty($this->groupssave) ? $this->groupssave : array();
        }
        if ($removeAll) {
            $dao = $bs->dao;
            $grouprelationBS = new GrouprelationBS();
            $grouprelationBS->addCondition("tableid", $idSave);
            $grouprelationBS->addCondition("tablename", $dao->useTable);
            $grouprelations = $grouprelationBS->all();
            foreach ($grouprelations as $grouprelation) {
                $bsDel = new GrouprelationBS();
                $bsDel->delete($grouprelation['Grouprelation']['id']);
            }
        }
        if (!ArrayUtility::isEmpty($groups)) {
            $dao = $bs->dao;
            foreach ($groups as $group) {
                $groupBS = new GroupBS();
                $groupBS->addCondition("cod", $group);
                $groupInstance = $groupBS->unique();

                $grouprelation = null;
                if (!$removeAll) {
                    $id_grouprelation = null;
                    $grouprelationBS = new GrouprelationBS();
                    $grouprelationBS->acceptNull = true;
                    $grouprelationBS->addCondition("group", $groupInstance['Group']['id']);
                    $grouprelationBS->addCondition("groupcod", $group);
                    $grouprelationBS->addCondition("tableid", $idSave);
                    $grouprelationBS->addCondition("tablename", $dao->useTable);
                    $grouprelation = $grouprelationBS->unique();
                }

                if (empty($grouprelation)) {
                    $grouprelationBS = new GrouprelationBS();
                    $grouprelation = $grouprelationBS->instance();
                    $grouprelation['Grouprelation']['cod'] = $group . "_" . FileUtility::uuid_short();
                    $grouprelation['Grouprelation']['group'] = $groupInstance['Group']['id'];
                    $grouprelation['Grouprelation']['groupcod'] = $group;
                    $grouprelation['Grouprelation']['tableid'] = $idSave;
                    $grouprelation['Grouprelation']['tablename'] = $dao->useTable;
                    $id_grouprelation = $grouprelationBS->save($grouprelation);
                } else {
                    $id_grouprelation = $grouprelation['Grouprelation']['id'];
                }

            }
        }
    }

    /**
     * Cancella una entity da uno o più gruppi o da tutti i gruppi se $allGroup = true
     *
     * @param  type $bs Oggetto business
     * @param  mixed $idSave id dell'entity
     * @param  boolean $allGroup se true indica di cancellare l'entity da tutti i gruppi
     * @return void
     */
    function delInGroup(AppGenericBS $bs, $idSave, $allGroup = false) {
        $groups = $this->getMergeSaveDelInGroup();

        if ($allGroup) {
            if ($this->json) {
                $groups = !empty($this->groupsdel) ? json_decode($this->groupsdel, true) : array();
            } else {
                $groups = !ArrayUtility::isEmpty($this->groupsdel) ? $this->groupsdel : array();
            }
            $dao = $bs->dao;
            $grouprelationBS = new GrouprelationBS();
            $grouprelationBS->addCondition("tableid", $idSave);
            $grouprelationBS->addCondition("tablename", $dao->useTable);
            $grouprelations = $grouprelationBS->all();
            foreach ($grouprelations as $grouprelation) {
                $bsDel = new GrouprelationBS();
                $bsDel->delete($grouprelation['Grouprelation']['id']);
            }
        } elseif (!ArrayUtility::isEmpty($groups)) {
            $dao = $bs->dao;
            foreach ($groups as $group) {
                $grouprelationBS = new GrouprelationBS();
                $grouprelationBS->addCondition("groupcod", $group);
                $grouprelationBS->addCondition("tableid", $idSave);
                $grouprelationBS->addCondition("tablename", $dao->useTable);
                $grouprelation = $grouprelationBS->unique();

                $bsDel = new GrouprelationBS();
                $bsDel->delete($grouprelation['Grouprelation']['id']);
            }
        }
    }

    /**
     * Controlla che nei gruppi da cancellare non ci siano quelli da inserire, in tal caso li rimuove dalla lista
     * @return mixed[] lista dei gruppi da cancellare ripulita da quelli presenti in inserimento
     */
    private function getMergeSaveDelInGroup() {
        if ($this->json) {
            $groupsIn = !empty($this->groupssave) ? json_decode($this->groupssave, true) : array();
            $groupsOut = !empty($this->groupsdel) ? json_decode($this->groupsdel, true) : array();
        } else {
            $groupsIn = !ArrayUtility::isEmpty($this->groupssave) ? $this->groupssave : array();
            $groupsOut = !ArrayUtility::isEmpty($this->groupsdel) ? $this->groupsdel : array();
        }

        if (ArrayUtility::isEmpty($groupsIn)) {
            return $groupsOut;
        }

        $newGroupsOut = array();

        foreach ($groupsOut as $groupOut) {
            if (!ArrayUtility::contains($groupsIn, $groupOut)) {
                array_push($newGroupsOut, $groupOut);
            }
        }

        return $newGroupsOut;
    }

    // conditions
    /**
     * Aggiunge automaticamente al business la foreign key se è presente nei filtri di ricerca
     * @param type $bs business da valutare
     * @param array $conditions lista di conditions
     */
    function evalConditions($bs, $conditions) {
        if ($this->json) {
            $jsonDecoder = new JsonDecoder();
            $conditions = $jsonDecoder->decodeMultiple($conditions, DBCondition::class);
        }
        if (!ArrayUtility::isEmpty($conditions)) {
            foreach ($conditions as $condition) {
                if (array_key_exists($condition->key, $bs->dao->arrayBelongsTo)) {
                    $bs->addBelongsTo($bs->dao, $condition->key);
                }
                if (array_key_exists($condition->key, $bs->dao->arrayVirtualFields)) {
                    $bs->addVirtualField($bs->dao, $condition->key);
                }
            }
        }
    }

    // ---------------------------- ORDERS
    function evalOrders($bs, $orders) {
        if ($this->json) {
            $jsonDecoder = new JsonDecoder();
            $orders = $jsonDecoder->decodeMultiple($orders, DBOrder::class);
        }
        if (!ArrayUtility::isEmpty($orders)) {
            foreach ($orders as $order) {
                if (array_key_exists($order->key, $bs->dao->arrayBelongsTo)) {
                    $bs->addBelongsTo($bs->dao, $order->key);
                }
                if (array_key_exists($order->key, $bs->dao->arrayVirtualFields)) {
                    $bs->addVirtualField($bs->dao, $order->key);
                }
            }
        }
    }

    function hasValueForSpecificOrderKey($orders, $keys) {
        $value = null;
        if ($this->json) {
            $jsonDecoder = new JsonDecoder();
            $orders = $jsonDecoder->decodeMultiple($orders, DBOrder::class);
        }
        if (!ArrayUtility::isEmpty($orders) && !ArrayUtility::isEmpty($keys)) {
            foreach ($keys as $key) {
                foreach ($orders as $order) {
                    if ($order->key == $key) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    function getOrders($orders) {
        if ($this->json) {
            $jsonDecoder = new JsonDecoder();
            $orders = $jsonDecoder->decodeMultiple($orders, DBOrder::class);
        }
        return $orders;
    }

    // ---------------------------- PAGINATION
    function evalPagination($bs, $paginate) {
        if ($this->json) {
            $jsonDecoder = new JsonDecoder();
            $paginate = $jsonDecoder->decode($paginate, DBPaginate::class);
        }
        if (!empty($paginate) && !empty($paginate->limit)) {
            $this->evalPaginationBusiness($bs, $paginate->limit);
            if ($paginate->page > $this->pages) {
                throw new Exception("Page not found into pagination", Codes::get("EXCEPTION_GENERIC"));
            }
        }
    }

    function evalPaginationBusiness($bs, $limit) {
        $this->count = $bs->getCountForPaginate();
        $this->pages = ceil($this->count / $limit);
    }

    function paginateForResponse($list) {
        $result = array(
            "list" => ($this->json ? json_decode($list, true) : $list),
            "count" => $this->count,
            "pages" => $this->pages,
        );
        return $this->json ? json_encode($result) : $result;
    }

    // ---------------------------- DATASOURCE
    function startTransaction() {
        if (!$this->transactional) {
            $this->datasource->begin();
            // debug("Transazione startata da ".$this->LOG_CLASS);
        }
    }

    function commitTransaction() {
        if (!$this->transactional) {
            $this->datasource->commit();
            // debug("Transazione committata da ".$this->LOG_CLASS);
        }
    }

    function rollbackTransaction() {
        $this->datasource->rollback();
        // debug("Transazione rollbackata da ".$this->LOG_CLASS);
    }
}