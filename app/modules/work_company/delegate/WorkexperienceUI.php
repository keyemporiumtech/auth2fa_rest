<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("WorkexperienceBS", "modules/work_company/business");
App::uses("FileUtility", "modules/coreutils/utility");

class WorkexperienceUI extends AppGenericUI {

    public function __construct() {
        parent::__construct("WorkexperienceUI");
        $this->localefile = "workexperience";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("title", null, ""),
            new ObjPropertyEntity("description", null, ""),
            new ObjPropertyEntity("company", null, 0),
            new ObjPropertyEntity("role", null, 0),
            new ObjPropertyEntity("place", null, ""),
            new ObjPropertyEntity("city", null, 0),
            new ObjPropertyEntity("nation", null, 0),
            new ObjPropertyEntity("dtainit", null, ""),
            new ObjPropertyEntity("dtaend", null, ""),
        );
    }

    public function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_WORKEXPERIENCE_NOT_FOUND");
                return "";
            }
            $workexperienceBS = new WorkexperienceBS();
            $workexperienceBS->json = $this->json;
            parent::completeByJsonFkVf($workexperienceBS);
            if (!empty($cod)) {
                $workexperienceBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $workexperienceBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_WORKEXPERIENCE_NOT_FOUND");
            return "";
        }
    }

    public function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $workexperienceBS = !empty($bs) ? $bs : new WorkexperienceBS();
            $workexperienceBS->json = $this->json;
            parent::completeByJsonFkVf($workexperienceBS);
            parent::evalConditions($workexperienceBS, $conditions);
            parent::evalOrders($workexperienceBS, $orders);
            $workexperiences = $workexperienceBS->table($conditions, $orders, $paginate);
            parent::evalPagination($workexperienceBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($workexperiences);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    public function save($workexperienceIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $workexperience = DelegateUtility::getEntityToSave(new WorkexperienceBS(), $workexperienceIn, $this->obj);

            if (!empty($workexperience)) {

                $workexperienceBS = new WorkexperienceBS();
                $id_workexperience = $workexperienceBS->save($workexperience);
                parent::saveInGroup($workexperienceBS, $id_workexperience);

                parent::commitTransaction();
                if (!empty($id_workexperience)) {
                    $this->ok(TranslatorUtility::__translate("INFO_WORKEXPERIENCE_SAVE", $this->localefile));
                    return $id_workexperience;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_WORKEXPERIENCE_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_WORKEXPERIENCE_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_WORKEXPERIENCE_SAVE");
            return 0;
        }
    }

    public function edit($id, $workexperienceIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $workexperience = DelegateUtility::getEntityToEdit(new WorkexperienceBS(), $workexperienceIn, $this->obj, $id);

            if (!empty($workexperience)) {
                $workexperienceBS = new WorkexperienceBS();
                $id_workexperience = $workexperienceBS->save($workexperience);
                parent::saveInGroup($workexperienceBS, $id_workexperience);
                parent::delInGroup($workexperienceBS, $id_workexperience);

                parent::commitTransaction();
                if (!empty($id_workexperience)) {
                    $this->ok(TranslatorUtility::__translate("INFO_WORKEXPERIENCE_EDIT", $this->localefile));
                    return $id_workexperience;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_WORKEXPERIENCE_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_WORKEXPERIENCE_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_WORKEXPERIENCE_EDIT");
            return 0;
        }
    }

    public function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $workexperienceBS = new WorkexperienceBS();
                $workexperienceBS->delete($id);
                parent::delInGroup($workexperienceBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_WORKEXPERIENCE_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_WORKEXPERIENCE_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_WORKEXPERIENCE_DELETE");
            return false;
        }
    }
}
