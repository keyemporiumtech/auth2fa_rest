<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("WorkexperienceroleBS", "modules/work_company/business");
App::uses("FileUtility", "modules/coreutils/utility");

class WorkexperienceroleUI extends AppGenericUI {

    public function __construct() {
        parent::__construct("WorkexperienceroleUI");
        $this->localefile = "workexperiencerole";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("months", null, 0.0),
            new ObjPropertyEntity("role", null, 0),
            new ObjPropertyEntity("experience", null, 0),
        );
    }

    public function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_WORKEXPERIENCEROLE_NOT_FOUND");
                return "";
            }
            $workexperienceroleBS = new WorkexperienceroleBS();
            $workexperienceroleBS->json = $this->json;
            parent::completeByJsonFkVf($workexperienceroleBS);
            if (!empty($cod)) {
                $workexperienceroleBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $workexperienceroleBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_WORKEXPERIENCEROLE_NOT_FOUND");
            return "";
        }
    }

    public function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $workexperienceroleBS = !empty($bs) ? $bs : new WorkexperienceroleBS();
            $workexperienceroleBS->json = $this->json;
            parent::completeByJsonFkVf($workexperienceroleBS);
            parent::evalConditions($workexperienceroleBS, $conditions);
            parent::evalOrders($workexperienceroleBS, $orders);
            $workexperienceroles = $workexperienceroleBS->table($conditions, $orders, $paginate);
            parent::evalPagination($workexperienceroleBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($workexperienceroles);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    public function save($workexperienceroleIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $workexperiencerole = DelegateUtility::getEntityToSave(new WorkexperienceroleBS(), $workexperienceroleIn, $this->obj);

            if (!empty($workexperiencerole)) {

                $workexperienceroleBS = new WorkexperienceroleBS();
                $id_workexperiencerole = $workexperienceroleBS->save($workexperiencerole);
                parent::saveInGroup($workexperienceroleBS, $id_workexperiencerole);

                parent::commitTransaction();
                if (!empty($id_workexperiencerole)) {
                    $this->ok(TranslatorUtility::__translate("INFO_WORKEXPERIENCEROLE_SAVE", $this->localefile));
                    return $id_workexperiencerole;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_WORKEXPERIENCEROLE_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_WORKEXPERIENCEROLE_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_WORKEXPERIENCEROLE_SAVE");
            return 0;
        }
    }

    public function edit($id, $workexperienceroleIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $workexperiencerole = DelegateUtility::getEntityToEdit(new WorkexperienceroleBS(), $workexperienceroleIn, $this->obj, $id);

            if (!empty($workexperiencerole)) {
                $workexperienceroleBS = new WorkexperienceroleBS();
                $id_workexperiencerole = $workexperienceroleBS->save($workexperiencerole);
                parent::saveInGroup($workexperienceroleBS, $id_workexperiencerole);
                parent::delInGroup($workexperienceroleBS, $id_workexperiencerole);

                parent::commitTransaction();
                if (!empty($id_workexperiencerole)) {
                    $this->ok(TranslatorUtility::__translate("INFO_WORKEXPERIENCEROLE_EDIT", $this->localefile));
                    return $id_workexperiencerole;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_WORKEXPERIENCEROLE_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_WORKEXPERIENCEROLE_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_WORKEXPERIENCEROLE_EDIT");
            return 0;
        }
    }

    public function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $workexperienceroleBS = new WorkexperienceroleBS();
                $workexperienceroleBS->delete($id);
                parent::delInGroup($workexperienceroleBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_WORKEXPERIENCEROLE_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_WORKEXPERIENCEROLE_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_WORKEXPERIENCEROLE_DELETE");
            return false;
        }
    }
}
