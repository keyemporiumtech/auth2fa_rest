<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("WorkexperienceskillBS", "modules/work_company/business");
App::uses("FileUtility", "modules/coreutils/utility");

class WorkexperienceskillUI extends AppGenericUI {

    public function __construct() {
        parent::__construct("WorkexperienceskillUI");
        $this->localefile = "workexperienceskill";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("months", null, 0.0),
            new ObjPropertyEntity("skill", null, 0),
            new ObjPropertyEntity("experience", null, 0),
            new ObjPropertyEntity("levelval", null, 0),
        );
    }

    public function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_WORKEXPERIENCESKILL_NOT_FOUND");
                return "";
            }
            $workexperienceskillBS = new WorkexperienceskillBS();
            $workexperienceskillBS->json = $this->json;
            parent::completeByJsonFkVf($workexperienceskillBS);
            if (!empty($cod)) {
                $workexperienceskillBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $workexperienceskillBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_WORKEXPERIENCESKILL_NOT_FOUND");
            return "";
        }
    }

    public function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $workexperienceskillBS = !empty($bs) ? $bs : new WorkexperienceskillBS();
            $workexperienceskillBS->json = $this->json;
            parent::completeByJsonFkVf($workexperienceskillBS);
            parent::evalConditions($workexperienceskillBS, $conditions);
            parent::evalOrders($workexperienceskillBS, $orders);
            $workexperienceskills = $workexperienceskillBS->table($conditions, $orders, $paginate);
            parent::evalPagination($workexperienceskillBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($workexperienceskills);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    public function save($workexperienceskillIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $workexperienceskill = DelegateUtility::getEntityToSave(new WorkexperienceskillBS(), $workexperienceskillIn, $this->obj);

            if (!empty($workexperienceskill)) {

                $workexperienceskillBS = new WorkexperienceskillBS();
                $id_workexperienceskill = $workexperienceskillBS->save($workexperienceskill);
                parent::saveInGroup($workexperienceskillBS, $id_workexperienceskill);

                parent::commitTransaction();
                if (!empty($id_workexperienceskill)) {
                    $this->ok(TranslatorUtility::__translate("INFO_WORKEXPERIENCESKILL_SAVE", $this->localefile));
                    return $id_workexperienceskill;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_WORKEXPERIENCESKILL_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_WORKEXPERIENCESKILL_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_WORKEXPERIENCESKILL_SAVE");
            return 0;
        }
    }

    public function edit($id, $workexperienceskillIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $workexperienceskill = DelegateUtility::getEntityToEdit(new WorkexperienceskillBS(), $workexperienceskillIn, $this->obj, $id);

            if (!empty($workexperienceskill)) {
                $workexperienceskillBS = new WorkexperienceskillBS();
                $id_workexperienceskill = $workexperienceskillBS->save($workexperienceskill);
                parent::saveInGroup($workexperienceskillBS, $id_workexperienceskill);
                parent::delInGroup($workexperienceskillBS, $id_workexperienceskill);

                parent::commitTransaction();
                if (!empty($id_workexperienceskill)) {
                    $this->ok(TranslatorUtility::__translate("INFO_WORKEXPERIENCESKILL_EDIT", $this->localefile));
                    return $id_workexperienceskill;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_WORKEXPERIENCESKILL_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_WORKEXPERIENCESKILL_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_WORKEXPERIENCESKILL_EDIT");
            return 0;
        }
    }

    public function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $workexperienceskillBS = new WorkexperienceskillBS();
                $workexperienceskillBS->delete($id);
                parent::delInGroup($workexperienceskillBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_WORKEXPERIENCESKILL_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_WORKEXPERIENCESKILL_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_WORKEXPERIENCESKILL_DELETE");
            return false;
        }
    }
}
