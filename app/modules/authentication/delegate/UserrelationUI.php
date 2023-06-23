<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("UserrelationBS", "modules/authentication/business");
App::uses("FileUtility", "modules/coreutils/utility");

class UserrelationUI extends AppGenericUI {

    function __construct() {
        parent::__construct("UserrelationUI");
        $this->localefile = "userrelation";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("user1", null, 0),
            new ObjPropertyEntity("user2", null, 0),
            new ObjPropertyEntity("tprelation", null, 0),
            new ObjPropertyEntity("inforelation1", null, ""),
            new ObjPropertyEntity("inforelation2", null, ""),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_USERRELATION_NOT_FOUND");
                return "";
            }
            $userrelationBS = new UserrelationBS();
            $userrelationBS->json = $this->json;
            parent::completeByJsonFkVf($userrelationBS);
            if (!empty($cod)) {
                $userrelationBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $userrelationBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_USERRELATION_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $userrelationBS = !empty($bs) ? $bs : new UserrelationBS();
            $userrelationBS->json = $this->json;
            parent::completeByJsonFkVf($userrelationBS);
            parent::evalConditions($userrelationBS, $conditions);
            parent::evalOrders($userrelationBS, $orders);
            $userrelations = $userrelationBS->table($conditions, $orders, $paginate);
            parent::evalPagination($userrelationBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($userrelations);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($userrelationIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $userrelation = DelegateUtility::getEntityToSave(new UserrelationBS(), $userrelationIn, $this->obj);

            if (!empty($userrelation)) {

                $userrelationBS = new UserrelationBS();
                $id_userrelation = $userrelationBS->save($userrelation);
                parent::saveInGroup($userrelationBS, $id_userrelation);

                parent::commitTransaction();
                if (!empty($id_userrelation)) {
                    $this->ok(TranslatorUtility::__translate("INFO_USERRELATION_SAVE", $this->localefile));
                    return $id_userrelation;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_USERRELATION_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_USERRELATION_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_USERRELATION_SAVE");
            return 0;
        }
    }

    function edit($id, $userrelationIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $userrelation = DelegateUtility::getEntityToEdit(new UserrelationBS(), $userrelationIn, $this->obj, $id);

            if (!empty($userrelation)) {
                $userrelationBS = new UserrelationBS();
                $id_userrelation = $userrelationBS->save($userrelation);
                parent::saveInGroup($userrelationBS, $id_userrelation);
                parent::delInGroup($userrelationBS, $id_userrelation);

                parent::commitTransaction();
                if (!empty($id_userrelation)) {
                    $this->ok(TranslatorUtility::__translate("INFO_USERRELATION_EDIT", $this->localefile));
                    return $id_userrelation;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_USERRELATION_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_USERRELATION_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_USERRELATION_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $userrelationBS = new UserrelationBS();
                $userrelationBS->delete($id);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_USERRELATION_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_USERRELATION_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_USERRELATION_DELETE");
            return false;
        }
    }

    // ---------------- TYPOLOGICAL
    function tpuserrelation($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "tpuserrelation";
        try {
            $typologicalUI = new TypologicalUI("Tpuserrelation", "authentication");
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
