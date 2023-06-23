<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("WorkskillBS", "modules/work_company/business");
App::uses("FileUtility", "modules/coreutils/utility");

class WorkskillUI extends AppGenericUI {

    public function __construct() {
        parent::__construct("WorkskillUI");
        $this->localefile = "workskill";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("name", null, ""),
            new ObjPropertyEntity("description", null, ""),
            new ObjPropertyEntity("tpskill", null, 0),
            new ObjPropertyEntity("levelmax", null, 0),
            new ObjPropertyEntity("leveldesc", null, ""),
        );
    }

    public function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_WORKSKILL_NOT_FOUND");
                return "";
            }
            $workskillBS = new WorkskillBS();
            $workskillBS->json = $this->json;
            parent::completeByJsonFkVf($workskillBS);
            if (!empty($cod)) {
                $workskillBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $workskillBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_WORKSKILL_NOT_FOUND");
            return "";
        }
    }

    public function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $workskillBS = !empty($bs) ? $bs : new WorkskillBS();
            $workskillBS->json = $this->json;
            parent::completeByJsonFkVf($workskillBS);
            parent::evalConditions($workskillBS, $conditions);
            parent::evalOrders($workskillBS, $orders);
            $workskills = $workskillBS->table($conditions, $orders, $paginate);
            parent::evalPagination($workskillBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($workskills);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    public function save($workskillIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $workskill = DelegateUtility::getEntityToSave(new WorkskillBS(), $workskillIn, $this->obj);

            if (!empty($workskill)) {

                $workskillBS = new WorkskillBS();
                $id_workskill = $workskillBS->save($workskill);
                parent::saveInGroup($workskillBS, $id_workskill);

                parent::commitTransaction();
                if (!empty($id_workskill)) {
                    $this->ok(TranslatorUtility::__translate("INFO_WORKSKILL_SAVE", $this->localefile));
                    return $id_workskill;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_WORKSKILL_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_WORKSKILL_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_WORKSKILL_SAVE");
            return 0;
        }
    }

    public function edit($id, $workskillIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $workskill = DelegateUtility::getEntityToEdit(new WorkskillBS(), $workskillIn, $this->obj, $id);

            if (!empty($workskill)) {
                $workskillBS = new WorkskillBS();
                $id_workskill = $workskillBS->save($workskill);
                parent::saveInGroup($workskillBS, $id_workskill);
                parent::delInGroup($workskillBS, $id_workskill);

                parent::commitTransaction();
                if (!empty($id_workskill)) {
                    $this->ok(TranslatorUtility::__translate("INFO_WORKSKILL_EDIT", $this->localefile));
                    return $id_workskill;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_WORKSKILL_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_WORKSKILL_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_WORKSKILL_EDIT");
            return 0;
        }
    }

    public function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $workskillBS = new WorkskillBS();
                $workskillBS->delete($id);
                parent::delInGroup($workskillBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_WORKSKILL_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_WORKSKILL_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_WORKSKILL_DELETE");
            return false;
        }
    }
}
