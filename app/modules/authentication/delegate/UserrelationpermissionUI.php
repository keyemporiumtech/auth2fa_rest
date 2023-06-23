<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("UserrelationpermissionBS", "modules/authentication/business");
App::uses("FileUtility", "modules/coreutils/utility");

class UserrelationpermissionUI extends AppGenericUI {

    function __construct() {
        parent::__construct("UserrelationpermissionUI");
        $this->localefile = "userrelationpermission";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("userrelation", null, 0),
            new ObjPropertyEntity("permission", null, 0),
            new ObjPropertyEntity("direction", null, 0),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_USERRELATIONPERMISSION_NOT_FOUND");
                return "";
            }
            $userrelationpermissionBS = new UserrelationpermissionBS();
            $userrelationpermissionBS->json = $this->json;
            parent::completeByJsonFkVf($userrelationpermissionBS);
            if (!empty($cod)) {
                $userrelationpermissionBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $userrelationpermissionBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_USERRELATIONPERMISSION_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $userrelationpermissionBS = !empty($bs) ? $bs : new UserrelationpermissionBS();
            $userrelationpermissionBS->json = $this->json;
            parent::completeByJsonFkVf($userrelationpermissionBS);
            parent::evalConditions($userrelationpermissionBS, $conditions);
            parent::evalOrders($userrelationpermissionBS, $orders);
            $userrelationpermissions = $userrelationpermissionBS->table($conditions, $orders, $paginate);
            parent::evalPagination($userrelationpermissionBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($userrelationpermissions);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($userrelationpermissionIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $userrelationpermission = DelegateUtility::getEntityToSave(new UserrelationpermissionBS(), $userrelationpermissionIn, $this->obj);

            if (!empty($userrelationpermission)) {

                $userrelationpermissionBS = new UserrelationpermissionBS();
                $id_userrelationpermission = $userrelationpermissionBS->save($userrelationpermission);
                parent::saveInGroup($userrelationpermissionBS, $id_userrelationpermission);

                parent::commitTransaction();
                if (!empty($id_userrelationpermission)) {
                    $this->ok(TranslatorUtility::__translate("INFO_USERRELATIONPERMISSION_SAVE", $this->localefile));
                    return $id_userrelationpermission;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_USERRELATIONPERMISSION_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_USERRELATIONPERMISSION_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_USERRELATIONPERMISSION_SAVE");
            return 0;
        }
    }

    function edit($id, $userrelationpermissionIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $userrelationpermission = DelegateUtility::getEntityToEdit(new UserrelationpermissionBS(), $userrelationpermissionIn, $this->obj, $id);

            if (!empty($userrelationpermission)) {
                $userrelationpermissionBS = new UserrelationpermissionBS();
                $id_userrelationpermission = $userrelationpermissionBS->save($userrelationpermission);
                parent::saveInGroup($userrelationpermissionBS, $id_userrelationpermission);
                parent::delInGroup($userrelationpermissionBS, $id_userrelationpermission);

                parent::commitTransaction();
                if (!empty($id_userrelationpermission)) {
                    $this->ok(TranslatorUtility::__translate("INFO_USERRELATIONPERMISSION_EDIT", $this->localefile));
                    return $id_userrelationpermission;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_USERRELATIONPERMISSION_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_USERRELATIONPERMISSION_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_USERRELATIONPERMISSION_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $userrelationpermissionBS = new UserrelationpermissionBS();
                $userrelationpermissionBS->delete($id);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_USERRELATIONPERMISSION_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_USERRELATIONPERMISSION_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_USERRELATIONPERMISSION_DELETE");
            return false;
        }
    }
}
