<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("ActivityrelationpermissionBS", "modules/authentication/business");
App::uses("FileUtility", "modules/coreutils/utility");

class ActivityrelationpermissionUI extends AppGenericUI {

    function __construct() {
        parent::__construct("ActivityrelationpermissionUI");
        $this->localefile = "activityrelationpermission";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("activityrelation", null, 0),
            new ObjPropertyEntity("permission", null, 0),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_ACTIVITYRELATIONPERMISSION_NOT_FOUND");
                return "";
            }
            $activityrelationpermissionBS = new ActivityrelationpermissionBS();
            $activityrelationpermissionBS->json = $this->json;
            parent::completeByJsonFkVf($activityrelationpermissionBS);
            if (!empty($cod)) {
                $activityrelationpermissionBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $activityrelationpermissionBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_ACTIVITYRELATIONPERMISSION_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $activityrelationpermissionBS = !empty($bs) ? $bs : new ActivityrelationpermissionBS();
            $activityrelationpermissionBS->json = $this->json;
            parent::completeByJsonFkVf($activityrelationpermissionBS);
            parent::evalConditions($activityrelationpermissionBS, $conditions);
            parent::evalOrders($activityrelationpermissionBS, $orders);
            $activityrelationpermissions = $activityrelationpermissionBS->table($conditions, $orders, $paginate);
            parent::evalPagination($activityrelationpermissionBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($activityrelationpermissions);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($activityrelationpermissionIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $activityrelationpermission = DelegateUtility::getEntityToSave(new ActivityrelationpermissionBS(), $activityrelationpermissionIn, $this->obj);

            if (!empty($activityrelationpermission)) {

                $activityrelationpermissionBS = new ActivityrelationpermissionBS();
                $id_activityrelationpermission = $activityrelationpermissionBS->save($activityrelationpermission);
                parent::saveInGroup($activityrelationpermissionBS, $id_activityrelationpermission);

                parent::commitTransaction();
                if (!empty($id_activityrelationpermission)) {
                    $this->ok(TranslatorUtility::__translate("INFO_ACTIVITYRELATIONPERMISSION_SAVE", $this->localefile));
                    return $id_activityrelationpermission;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_ACTIVITYRELATIONPERMISSION_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_ACTIVITYRELATIONPERMISSION_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_ACTIVITYRELATIONPERMISSION_SAVE");
            return 0;
        }
    }

    function edit($id, $activityrelationpermissionIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $activityrelationpermission = DelegateUtility::getEntityToEdit(new ActivityrelationpermissionBS(), $activityrelationpermissionIn, $this->obj, $id);

            if (!empty($activityrelationpermission)) {
                $activityrelationpermissionBS = new ActivityrelationpermissionBS();
                $id_activityrelationpermission = $activityrelationpermissionBS->save($activityrelationpermission);
                parent::saveInGroup($activityrelationpermissionBS, $id_activityrelationpermission);
                parent::delInGroup($activityrelationpermissionBS, $id_activityrelationpermission);

                parent::commitTransaction();
                if (!empty($id_activityrelationpermission)) {
                    $this->ok(TranslatorUtility::__translate("INFO_ACTIVITYRELATIONPERMISSION_EDIT", $this->localefile));
                    return $id_activityrelationpermission;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_ACTIVITYRELATIONPERMISSION_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_ACTIVITYRELATIONPERMISSION_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_ACTIVITYRELATIONPERMISSION_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $activityrelationpermissionBS = new ActivityrelationpermissionBS();
                $activityrelationpermissionBS->delete($id);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_ACTIVITYRELATIONPERMISSION_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_ACTIVITYRELATIONPERMISSION_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_ACTIVITYRELATIONPERMISSION_DELETE");
            return false;
        }
    }
}
