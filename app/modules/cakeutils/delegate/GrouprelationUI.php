<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("GrouprelationBS", "modules/cakeutils/business");
App::uses("FileUtility", "modules/coreutils/utility");

class GrouprelationUI extends AppGenericUI {

    function __construct() {
        parent::__construct("GrouprelationUI");
        $this->localefile = "grouprelation";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("name", null, ""),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_GROUPRELATION_NOT_FOUND");
                return "";
            }
            $grouprelationBS = new GrouprelationBS();
            $grouprelationBS->json = $this->json;
            parent::completeByJsonFkVf($grouprelationBS);
            if (!empty($cod)) {
                $grouprelationBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $grouprelationBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_GROUPRELATION_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $grouprelationBS = !empty($bs) ? $bs : new GrouprelationBS();
            $grouprelationBS->json = $this->json;
            parent::completeByJsonFkVf($grouprelationBS);
            parent::evalConditions($grouprelationBS, $conditions);
            parent::evalOrders($grouprelationBS, $orders);
            $grouprelations = $grouprelationBS->table($conditions, $orders, $paginate);
            parent::evalPagination($grouprelationBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($grouprelations);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($grouprelationIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $grouprelation = DelegateUtility::getEntityToSave(new GrouprelationBS(), $grouprelationIn, $this->obj);

            if (!empty($grouprelation)) {

                $grouprelationBS = new GrouprelationBS();
                $id_grouprelation = $grouprelationBS->save($grouprelation);
                parent::saveInGroup($grouprelationBS, $id_grouprelation);

                parent::commitTransaction();
                if (!empty($id_grouprelation)) {
                    $this->ok(TranslatorUtility::__translate("INFO_GROUPRELATION_SAVE", $this->localefile));
                    return $id_grouprelation;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_GROUPRELATION_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_GROUPRELATION_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_GROUPRELATION_SAVE");
            return 0;
        }
    }

    function edit($id, $grouprelationIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $grouprelation = DelegateUtility::getEntityToEdit(new GrouprelationBS(), $grouprelationIn, $this->obj, $id);

            if (!empty($grouprelation)) {
                $grouprelationBS = new GrouprelationBS();
                $id_grouprelation = $grouprelationBS->save($grouprelation);
                parent::saveInGroup($grouprelationBS, $id_grouprelation);
                parent::delInGroup($grouprelationBS, $id_grouprelation);

                parent::commitTransaction();
                if (!empty($id_grouprelation)) {
                    $this->ok(TranslatorUtility::__translate("INFO_GROUPRELATION_EDIT", $this->localefile));
                    return $id_grouprelation;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_GROUPRELATION_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_GROUPRELATION_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_GROUPRELATION_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $grouprelationBS = new GrouprelationBS();
                $grouprelationBS->delete($id);
                parent::delInGroup($grouprelationBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_GROUPRELATION_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_GROUPRELATION_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_GROUPRELATION_DELETE");
            return false;
        }
    }
}
