<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("PermissionBS", "modules/authentication/business");
App::uses("FileUtility", "modules/coreutils/utility");

class PermissionUI extends AppGenericUI {

    function __construct() {
        parent::__construct("PermissionUI");
        $this->localefile = "permission";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("name", null, ""),
            new ObjPropertyEntity("description", null, ""),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_PERMISSION_NOT_FOUND");
                return "";
            }
            $permissionBS = new PermissionBS();
            $permissionBS->json = $this->json;
            parent::completeByJsonFkVf($permissionBS);
            if (!empty($cod)) {
                $permissionBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $permissionBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_PERMISSION_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $permissionBS = !empty($bs) ? $bs : new PermissionBS();
            $permissionBS->json = $this->json;
            parent::completeByJsonFkVf($permissionBS);
            parent::evalConditions($permissionBS, $conditions);
            parent::evalOrders($permissionBS, $orders);
            $permissions = $permissionBS->table($conditions, $orders, $paginate);
            parent::evalPagination($permissionBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($permissions);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($permissionIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $permission = DelegateUtility::getEntityToSave(new PermissionBS(), $permissionIn, $this->obj);

            if (!empty($permission)) {

                $permissionBS = new PermissionBS();
                $id_permission = $permissionBS->save($permission);
                parent::saveInGroup($permissionBS, $id_permission);

                parent::commitTransaction();
                if (!empty($id_permission)) {
                    $this->ok(TranslatorUtility::__translate("INFO_PERMISSION_SAVE", $this->localefile));
                    return $id_permission;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_PERMISSION_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_PERMISSION_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PERMISSION_SAVE");
            return 0;
        }
    }

    function edit($id, $permissionIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $permission = DelegateUtility::getEntityToEdit(new PermissionBS(), $permissionIn, $this->obj, $id);

            if (!empty($permission)) {
                $permissionBS = new PermissionBS();
                $id_permission = $permissionBS->save($permission);
                parent::saveInGroup($permissionBS, $id_permission);
                parent::delInGroup($permissionBS, $id_permission);

                parent::commitTransaction();
                if (!empty($id_permission)) {
                    $this->ok(TranslatorUtility::__translate("INFO_PERMISSION_EDIT", $this->localefile));
                    return $id_permission;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_PERMISSION_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_PERMISSION_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PERMISSION_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $permissionBS = new PermissionBS();
                $permissionBS->delete($id);
                parent::delInGroup($permissionBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_PERMISSION_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_PERMISSION_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PERMISSION_DELETE");
            return false;
        }
    }
}
