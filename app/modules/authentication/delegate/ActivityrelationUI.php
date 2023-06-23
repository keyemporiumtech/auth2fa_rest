<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("ActivityrelationBS", "modules/authentication/business");
App::uses("FileUtility", "modules/coreutils/utility");

class ActivityrelationUI extends AppGenericUI {

    function __construct() {
        parent::__construct("ActivityrelationUI");
        $this->localefile = "activityrelation";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("user", null, 0),
            new ObjPropertyEntity("activity", null, 0),
            new ObjPropertyEntity("tprelation", null, 0),
            new ObjPropertyEntity("inforelationuser", null, ""),
            new ObjPropertyEntity("inforelationactivity", null, ""),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_ACTIVITYRELATION_NOT_FOUND");
                return "";
            }
            $activityrelationBS = new ActivityrelationBS();
            $activityrelationBS->json = $this->json;
            parent::completeByJsonFkVf($activityrelationBS);
            if (!empty($cod)) {
                $activityrelationBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $activityrelationBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_ACTIVITYRELATION_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $activityrelationBS = !empty($bs) ? $bs : new ActivityrelationBS();
            $activityrelationBS->json = $this->json;
            parent::completeByJsonFkVf($activityrelationBS);
            parent::evalConditions($activityrelationBS, $conditions);
            parent::evalOrders($activityrelationBS, $orders);
            $activityrelations = $activityrelationBS->table($conditions, $orders, $paginate);
            parent::evalPagination($activityrelationBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($activityrelations);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($activityrelationIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $activityrelation = DelegateUtility::getEntityToSave(new ActivityrelationBS(), $activityrelationIn, $this->obj);

            if (!empty($activityrelation)) {

                $activityrelationBS = new ActivityrelationBS();
                $id_activityrelation = $activityrelationBS->save($activityrelation);
                parent::saveInGroup($activityrelationBS, $id_activityrelation);

                parent::commitTransaction();
                if (!empty($id_activityrelation)) {
                    $this->ok(TranslatorUtility::__translate("INFO_ACTIVITYRELATION_SAVE", $this->localefile));
                    return $id_activityrelation;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_ACTIVITYRELATION_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_ACTIVITYRELATION_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_ACTIVITYRELATION_SAVE");
            return 0;
        }
    }

    function edit($id, $activityrelationIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $activityrelation = DelegateUtility::getEntityToEdit(new ActivityrelationBS(), $activityrelationIn, $this->obj, $id);

            if (!empty($activityrelation)) {
                $activityrelationBS = new ActivityrelationBS();
                $id_activityrelation = $activityrelationBS->save($activityrelation);
                parent::saveInGroup($activityrelationBS, $id_activityrelation);
                parent::delInGroup($activityrelationBS, $id_activityrelation);

                parent::commitTransaction();
                if (!empty($id_activityrelation)) {
                    $this->ok(TranslatorUtility::__translate("INFO_ACTIVITYRELATION_EDIT", $this->localefile));
                    return $id_activityrelation;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_ACTIVITYRELATION_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_ACTIVITYRELATION_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_ACTIVITYRELATION_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $activityrelationBS = new ActivityrelationBS();
                $activityrelationBS->delete($id);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_ACTIVITYRELATION_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_ACTIVITYRELATION_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_ACTIVITYRELATION_DELETE");
            return false;
        }
    }

    // ---------------- TYPOLOGICAL
    function tpactivityrelation($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "tpactivityrelation";
        try {
            $typologicalUI = new TypologicalUI("Tpactivityrelation", "authentication");
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
