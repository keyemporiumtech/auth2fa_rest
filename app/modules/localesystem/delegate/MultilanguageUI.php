<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("MultilanguageBS", "modules/localesystem/business");

class MultilanguageUI extends AppGenericUI {

    function __construct() {
        parent::__construct("MultilanguageUI");
        $this->localefile = "multilanguage";
        $this->obj = array(
            new ObjPropertyEntity("tablename", null, ""),
            new ObjPropertyEntity("fieldname", null, ""),
            new ObjPropertyEntity("content", null, ""),
            new ObjPropertyEntity("objraw", null, 0),
            new ObjPropertyEntity("languageid", null, 0),
            new ObjPropertyEntity("languagecod", null, ""),
            new ObjPropertyEntity("type", null, ""),
        );
    }

    function get($id = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id)) {
                DelegateUtility::paramsNull($this, "ERROR_MULTILANGUAGE_NOT_FOUND");
                return "";
            }
            $multilanguageBS = new MultilanguageBS();
            $multilanguageBS->json = $this->json;
            parent::completeByJsonFkVf($multilanguageBS);
            $this->ok();
            return $multilanguageBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_MULTILANGUAGE_NOT_FOUND");
            return "";
        }
    }

    function getByField($table = null, $field = null, $objraw = null, $languageid = null, $languagecod = null) {
        $this->LOG_FUNCTION = "getByField";
        try {
            if (empty($table) || empty($field) || empty($objraw) || (empty($languageid) && empty($languagecod))) {
                DelegateUtility::paramsNull($this, "ERROR_MULTILANGUAGE_NOT_FOUND");
                return "";
            }
            $multilanguageBS = new MultilanguageBS();
            $multilanguageBS->json = $this->json;
            parent::completeByJsonFkVf($multilanguageBS);
            $multilanguageBS->addCondition("tablename", $table);
            $multilanguageBS->addCondition("fieldname", $field);
            $multilanguageBS->addCondition("objraw", $objraw);
            if (!empty($languageid)) {
                $multilanguageBS->addCondition("languageid", $languageid);
            }
            if (!empty($languagecod)) {
                $multilanguageBS->addCondition("languagecod", $languagecod);
            }
            $this->ok();
            return $multilanguageBS->unique();
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_MULTILANGUAGE_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $multilanguageBS = !empty($bs) ? $bs : new MultilanguageBS();
            $multilanguageBS->json = $this->json;
            parent::completeByJsonFkVf($multilanguageBS);
            parent::evalConditions($multilanguageBS, $conditions);
            parent::evalOrders($multilanguageBS, $orders);
            $multilanguages = $multilanguageBS->table($conditions, $orders, $paginate);
            parent::evalPagination($multilanguageBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($multilanguages);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($multilanguageIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $multilanguage = DelegateUtility::getEntityToSave(new MultilanguageBS(), $multilanguageIn, $this->obj);

            if (!empty($multilanguage)) {

                $multilanguageBS = new MultilanguageBS();
                $id_multilanguage = $multilanguageBS->save($multilanguage);
                parent::saveInGroup($multilanguageBS, $id_multilanguage);

                parent::commitTransaction();
                if (!empty($id_multilanguage)) {
                    $this->ok(TranslatorUtility::__translate("INFO_MULTILANGUAGE_SAVE", $this->localefile));
                    return $id_multilanguage;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_MULTILANGUAGE_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_MULTILANGUAGE_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_MULTILANGUAGE_SAVE");
            return 0;
        }
    }

    function edit($id, $multilanguageIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $multilanguage = DelegateUtility::getEntityToEdit(new MultilanguageBS(), $multilanguageIn, $this->obj, $id);

            if (!empty($multilanguage)) {
                $multilanguageBS = new MultilanguageBS();
                $id_multilanguage = $multilanguageBS->save($multilanguage);
                parent::saveInGroup($multilanguageBS, $id_multilanguage);
                parent::delInGroup($multilanguageBS, $id_multilanguage);

                parent::commitTransaction();
                if (!empty($id_multilanguage)) {
                    $this->ok(TranslatorUtility::__translate("INFO_MULTILANGUAGE_EDIT", $this->localefile));
                    return $id_multilanguage;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_MULTILANGUAGE_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_MULTILANGUAGE_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_MULTILANGUAGE_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $multilanguageBS = new MultilanguageBS();
                $multilanguageBS->delete($id);
                parent::delInGroup($multilanguageBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_MULTILANGUAGE_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_MULTILANGUAGE_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_MULTILANGUAGE_DELETE");
            return false;
        }
    }

    // TYPOLOGICAL
    function tplanguagesfield($table = null, $field = null, $objraw = null) {
        $this->LOG_FUNCTION = "tplanguagesfield";
        try {
            if (empty($table) || empty($field) || empty($objraw)) {
                DelegateUtility::paramsNull($this, "ERROR_MULTILANGUAGE_NOT_FOUND");
                return "";
            }
            $multilanguageBS = new MultilanguageBS();
            $multilanguageBS->addCondition("tablename", $table);
            $multilanguageBS->addCondition("fieldname", $field);
            $multilanguageBS->addCondition("objraw", $objraw);
            $list = $multilanguageBS->all();
            $result = array();
            foreach ($list as $multilanguage) {
                array_push($result, strtolower($multilanguage['Multilanguage']['languagecod']));
            }
            $this->ok();
            return $this->json ? json_encode($result, true) : false;
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_MULTILANGUAGE_NOT_FOUND");
            return "";
        }
    }
}
