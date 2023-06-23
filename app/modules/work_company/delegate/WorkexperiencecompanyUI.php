<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("WorkexperiencecompanyBS", "modules/work_company/business");
App::uses("FileUtility", "modules/coreutils/utility");

class WorkexperiencecompanyUI extends AppGenericUI {

    public function __construct() {
        parent::__construct("WorkexperiencecompanyUI");
        $this->localefile = "workexperiencecompany";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("company", null, 0),
            new ObjPropertyEntity("experience", null, 0),
        );
    }

    public function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_WORKEXPERIENCECOMPANY_NOT_FOUND");
                return "";
            }
            $workexperiencecompanyBS = new WorkexperiencecompanyBS();
            $workexperiencecompanyBS->json = $this->json;
            parent::completeByJsonFkVf($workexperiencecompanyBS);
            if (!empty($cod)) {
                $workexperiencecompanyBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $workexperiencecompanyBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_WORKEXPERIENCECOMPANY_NOT_FOUND");
            return "";
        }
    }

    public function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $workexperiencecompanyBS = !empty($bs) ? $bs : new WorkexperiencecompanyBS();
            $workexperiencecompanyBS->json = $this->json;
            parent::completeByJsonFkVf($workexperiencecompanyBS);
            parent::evalConditions($workexperiencecompanyBS, $conditions);
            parent::evalOrders($workexperiencecompanyBS, $orders);
            $workexperiencecompanys = $workexperiencecompanyBS->table($conditions, $orders, $paginate);
            parent::evalPagination($workexperiencecompanyBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($workexperiencecompanys);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    public function save($workexperiencecompanyIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $workexperiencecompany = DelegateUtility::getEntityToSave(new WorkexperiencecompanyBS(), $workexperiencecompanyIn, $this->obj);

            if (!empty($workexperiencecompany)) {

                $workexperiencecompanyBS = new WorkexperiencecompanyBS();
                $id_workexperiencecompany = $workexperiencecompanyBS->save($workexperiencecompany);
                parent::saveInGroup($workexperiencecompanyBS, $id_workexperiencecompany);

                parent::commitTransaction();
                if (!empty($id_workexperiencecompany)) {
                    $this->ok(TranslatorUtility::__translate("INFO_WORKEXPERIENCECOMPANY_SAVE", $this->localefile));
                    return $id_workexperiencecompany;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_WORKEXPERIENCECOMPANY_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_WORKEXPERIENCECOMPANY_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_WORKEXPERIENCECOMPANY_SAVE");
            return 0;
        }
    }

    public function edit($id, $workexperiencecompanyIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $workexperiencecompany = DelegateUtility::getEntityToEdit(new WorkexperiencecompanyBS(), $workexperiencecompanyIn, $this->obj, $id);

            if (!empty($workexperiencecompany)) {
                $workexperiencecompanyBS = new WorkexperiencecompanyBS();
                $id_workexperiencecompany = $workexperiencecompanyBS->save($workexperiencecompany);
                parent::saveInGroup($workexperiencecompanyBS, $id_workexperiencecompany);
                parent::delInGroup($workexperiencecompanyBS, $id_workexperiencecompany);

                parent::commitTransaction();
                if (!empty($id_workexperiencecompany)) {
                    $this->ok(TranslatorUtility::__translate("INFO_WORKEXPERIENCECOMPANY_EDIT", $this->localefile));
                    return $id_workexperiencecompany;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_WORKEXPERIENCECOMPANY_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_WORKEXPERIENCECOMPANY_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_WORKEXPERIENCECOMPANY_EDIT");
            return 0;
        }
    }

    public function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $workexperiencecompanyBS = new WorkexperiencecompanyBS();
                $workexperiencecompanyBS->delete($id);
                parent::delInGroup($workexperiencecompanyBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_WORKEXPERIENCECOMPANY_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_WORKEXPERIENCECOMPANY_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_WORKEXPERIENCECOMPANY_DELETE");
            return false;
        }
    }
}
