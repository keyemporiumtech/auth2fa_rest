<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("WorkroleBS", "modules/work_company/business");
App::uses("FileUtility", "modules/coreutils/utility");

class WorkroleUI extends AppGenericUI {

    public function __construct() {
        parent::__construct("WorkroleUI");
        $this->localefile = "workrole";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("name", null, ""),
            new ObjPropertyEntity("description", null, ""),
        );
    }

    public function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_WORKROLE_NOT_FOUND");
                return "";
            }
            $workroleBS = new WorkroleBS();
            $workroleBS->json = $this->json;
            parent::completeByJsonFkVf($workroleBS);
            if (!empty($cod)) {
                $workroleBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $workroleBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_WORKROLE_NOT_FOUND");
            return "";
        }
    }

    public function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $workroleBS = !empty($bs) ? $bs : new WorkroleBS();
            $workroleBS->json = $this->json;
            parent::completeByJsonFkVf($workroleBS);
            parent::evalConditions($workroleBS, $conditions);
            parent::evalOrders($workroleBS, $orders);
            $workroles = $workroleBS->table($conditions, $orders, $paginate);
            parent::evalPagination($workroleBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($workroles);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    public function save($workroleIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $workrole = DelegateUtility::getEntityToSave(new WorkroleBS(), $workroleIn, $this->obj);

            if (!empty($workrole)) {

                $workroleBS = new WorkroleBS();
                $id_workrole = $workroleBS->save($workrole);
                parent::saveInGroup($workroleBS, $id_workrole);

                parent::commitTransaction();
                if (!empty($id_workrole)) {
                    $this->ok(TranslatorUtility::__translate("INFO_WORKROLE_SAVE", $this->localefile));
                    return $id_workrole;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_WORKROLE_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_WORKROLE_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_WORKROLE_SAVE");
            return 0;
        }
    }

    public function edit($id, $workroleIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $workrole = DelegateUtility::getEntityToEdit(new WorkroleBS(), $workroleIn, $this->obj, $id);

            if (!empty($workrole)) {
                $workroleBS = new WorkroleBS();
                $id_workrole = $workroleBS->save($workrole);
                parent::saveInGroup($workroleBS, $id_workrole);
                parent::delInGroup($workroleBS, $id_workrole);

                parent::commitTransaction();
                if (!empty($id_workrole)) {
                    $this->ok(TranslatorUtility::__translate("INFO_WORKROLE_EDIT", $this->localefile));
                    return $id_workrole;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_WORKROLE_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_WORKROLE_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_WORKROLE_EDIT");
            return 0;
        }
    }

    public function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $workroleBS = new WorkroleBS();
                $workroleBS->delete($id);
                parent::delInGroup($workroleBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_WORKROLE_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_WORKROLE_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_WORKROLE_DELETE");
            return false;
        }
    }
}
