<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("ContactreferenceBS", "modules/authentication/business");
App::uses("FileUtility", "modules/coreutils/utility");
App::uses("TypologicalUI", "modules/cakeutils/delegate");

class ContactreferenceUI extends AppGenericUI {

    function __construct() {
        parent::__construct("ContactreferenceUI");
        $this->localefile = "contactreference";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("val", null, ""),
            new ObjPropertyEntity("description", null, ""),
            new ObjPropertyEntity("tpcontactreference", null, 0),
            new ObjPropertyEntity("tpsocialreference", null, 0),
            new ObjPropertyEntity("prefix", null, ""),
            new ObjPropertyEntity("flgused", null, 0),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_CONTACTREFERENCE_NOT_FOUND");
                return "";
            }
            $contactreferenceBS = new ContactreferenceBS();
            $contactreferenceBS->json = $this->json;
            parent::completeByJsonFkVf($contactreferenceBS);
            if (!empty($cod)) {
                $contactreferenceBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $contactreferenceBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_CONTACTREFERENCE_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $contactreferenceBS = !empty($bs) ? $bs : new ContactreferenceBS();
            $contactreferenceBS->json = $this->json;
            parent::completeByJsonFkVf($contactreferenceBS);
            parent::evalConditions($contactreferenceBS, $conditions);
            parent::evalOrders($contactreferenceBS, $orders);
            $contactreferences = $contactreferenceBS->table($conditions, $orders, $paginate);
            parent::evalPagination($contactreferenceBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($contactreferences);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($contactreferenceIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $contactreference = DelegateUtility::getEntityToSave(new ContactreferenceBS(), $contactreferenceIn, $this->obj);

            if (!empty($contactreference)) {

                $contactreferenceBS = new ContactreferenceBS();
                $id_contactreference = $contactreferenceBS->save($contactreference);
                parent::saveInGroup($contactreferenceBS, $id_contactreference);

                parent::commitTransaction();
                if (!empty($id_contactreference)) {
                    $this->ok(TranslatorUtility::__translate("INFO_CONTACTREFERENCE_SAVE", $this->localefile));
                    return $id_contactreference;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_CONTACTREFERENCE_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_CONTACTREFERENCE_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_CONTACTREFERENCE_SAVE");
            return 0;
        }
    }

    function edit($id, $contactreferenceIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $contactreference = DelegateUtility::getEntityToEdit(new ContactreferenceBS(), $contactreferenceIn, $this->obj, $id);

            if (!empty($contactreference)) {
                $contactreferenceBS = new ContactreferenceBS();
                $id_contactreference = $contactreferenceBS->save($contactreference);
                parent::saveInGroup($contactreferenceBS, $id_contactreference);
                parent::delInGroup($contactreferenceBS, $id_contactreference);

                parent::commitTransaction();
                if (!empty($id_contactreference)) {
                    $this->ok(TranslatorUtility::__translate("INFO_CONTACTREFERENCE_EDIT", $this->localefile));
                    return $id_contactreference;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_CONTACTREFERENCE_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_CONTACTREFERENCE_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_CONTACTREFERENCE_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $contactreferenceBS = new ContactreferenceBS();
                $contactreferenceBS->delete($id);
                parent::delInGroup($contactreferenceBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_CONTACTREFERENCE_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_CONTACTREFERENCE_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_CONTACTREFERENCE_DELETE");
            return false;
        }
    }

    // ---------------- TYPOLOGICAL
    function tpcontactreference($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "tpcontactreference";
        try {
            $typologicalUI = new TypologicalUI("Tpcontactreference", "authentication");
            $typologicalUI->json = $this->json;
            parent::assignToDelegate($typologicalUI);
            $result = $typologicalUI->table($conditions, $orders, $paginate, $bs);
            parent::mappingDelegate($typologicalUI);
            return $result;
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function tpsocialreference($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "tpsocialreference";
        try {
            $typologicalUI = new TypologicalUI("Tpsocialreference", "authentication");
            $typologicalUI->json = $this->json;
            parent::assignToDelegate($typologicalUI);
            $result = $typologicalUI->table($conditions, $orders, $paginate, $bs);
            parent::mappingDelegate($typologicalUI);
            return $result;
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }
}
