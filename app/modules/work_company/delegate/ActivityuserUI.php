<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("ActivityuserBS", "modules/work_company/business");
App::uses("FileUtility", "modules/coreutils/utility");

class ActivityuserUI extends AppGenericUI {

    public function __construct() {
        parent::__construct("ActivityuserUI");
        $this->localefile = "activityuser";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("user", null, 0),
            new ObjPropertyEntity("activity", null, 0),
        );
    }

    public function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_ACTIVITYUSER_NOT_FOUND");
                return "";
            }
            $activityuserBS = new ActivityuserBS();
            $activityuserBS->json = $this->json;
            parent::completeByJsonFkVf($activityuserBS);
            if (!empty($cod)) {
                $activityuserBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $activityuserBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_ACTIVITYUSER_NOT_FOUND");
            return "";
        }
    }

    public function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $activityuserBS = !empty($bs) ? $bs : new ActivityuserBS();
            $activityuserBS->json = $this->json;
            parent::completeByJsonFkVf($activityuserBS);
            parent::evalConditions($activityuserBS, $conditions);
            parent::evalOrders($activityuserBS, $orders);
            $activityusers = $activityuserBS->table($conditions, $orders, $paginate);
            parent::evalPagination($activityuserBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($activityusers);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    public function save($activityuserIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $activityuser = DelegateUtility::getEntityToSave(new ActivityuserBS(), $activityuserIn, $this->obj);

            if (!empty($activityuser)) {

                $activityuserBS = new ActivityuserBS();
                $id_activityuser = $activityuserBS->save($activityuser);
                parent::saveInGroup($activityuserBS, $id_activityuser);

                parent::commitTransaction();
                if (!empty($id_activityuser)) {
                    $this->ok(TranslatorUtility::__translate("INFO_ACTIVITYUSER_SAVE", $this->localefile));
                    return $id_activityuser;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_ACTIVITYUSER_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_ACTIVITYUSER_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_ACTIVITYUSER_SAVE");
            return 0;
        }
    }

    public function edit($id, $activityuserIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $activityuser = DelegateUtility::getEntityToEdit(new ActivityuserBS(), $activityuserIn, $this->obj, $id);

            if (!empty($activityuser)) {
                $activityuserBS = new ActivityuserBS();
                $id_activityuser = $activityuserBS->save($activityuser);
                parent::saveInGroup($activityuserBS, $id_activityuser);
                parent::delInGroup($activityuserBS, $id_activityuser);

                parent::commitTransaction();
                if (!empty($id_activityuser)) {
                    $this->ok(TranslatorUtility::__translate("INFO_ACTIVITYUSER_EDIT", $this->localefile));
                    return $id_activityuser;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_ACTIVITYUSER_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_ACTIVITYUSER_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_ACTIVITYUSER_EDIT");
            return 0;
        }
    }

    public function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $activityuserBS = new ActivityuserBS();
                $activityuserBS->delete($id);
                parent::delInGroup($activityuserBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_ACTIVITYUSER_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_ACTIVITYUSER_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_ACTIVITYUSER_DELETE");
            return false;
        }
    }
}
