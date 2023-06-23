<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("ProfilepermissionBS", "modules/authentication/business");
App::uses("ProfileBS", "modules/authentication/business");
App::uses("FileUtility", "modules/coreutils/utility");

class ProfilepermissionUI extends AppGenericUI {

    function __construct() {
        parent::__construct("ProfilepermissionUI");
        $this->localefile = "profilepermission";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("profile", null, 0),
            new ObjPropertyEntity("permission", null, 0),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_PROFILEPERMISSION_NOT_FOUND");
                return "";
            }
            $profilepermissionBS = new ProfilepermissionBS();
            $profilepermissionBS->json = $this->json;
            parent::completeByJsonFkVf($profilepermissionBS);
            if (!empty($cod)) {
                $profilepermissionBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $profilepermissionBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_PROFILEPERMISSION_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $profilepermissionBS = !empty($bs) ? $bs : new ProfilepermissionBS();
            $profilepermissionBS->json = $this->json;
            parent::completeByJsonFkVf($profilepermissionBS);
            parent::evalConditions($profilepermissionBS, $conditions);
            parent::evalOrders($profilepermissionBS, $orders);
            $profilepermissions = $profilepermissionBS->table($conditions, $orders, $paginate);
            parent::evalPagination($profilepermissionBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($profilepermissions);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($profilepermissionIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $profilepermission = DelegateUtility::getEntityToSave(new ProfilepermissionBS(), $profilepermissionIn, $this->obj);

            if (!empty($profilepermission)) {

                $profilepermissionBS = new ProfilepermissionBS();
                $id_profilepermission = $profilepermissionBS->save($profilepermission);
                parent::saveInGroup($profilepermissionBS, $id_profilepermission);

                parent::commitTransaction();
                if (!empty($id_profilepermission)) {
                    $this->ok(TranslatorUtility::__translate("INFO_PROFILEPERMISSION_SAVE", $this->localefile));
                    return $id_profilepermission;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_PROFILEPERMISSION_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_PROFILEPERMISSION_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PROFILEPERMISSION_SAVE");
            return 0;
        }
    }

    function edit($id, $profilepermissionIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $profilepermission = DelegateUtility::getEntityToEdit(new ProfilepermissionBS(), $profilepermissionIn, $this->obj, $id);

            if (!empty($profilepermission)) {
                $profilepermissionBS = new ProfilepermissionBS();
                $id_profilepermission = $profilepermissionBS->save($profilepermission);
                parent::saveInGroup($profilepermissionBS, $id_profilepermission);
                parent::delInGroup($profilepermissionBS, $id_profilepermission);

                parent::commitTransaction();
                if (!empty($id_profilepermission)) {
                    $this->ok(TranslatorUtility::__translate("INFO_PROFILEPERMISSION_EDIT", $this->localefile));
                    return $id_profilepermission;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_PROFILEPERMISSION_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_PROFILEPERMISSION_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PROFILEPERMISSION_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $profilepermissionBS = new ProfilepermissionBS();
                $profilepermissionBS->delete($id);
                parent::delInGroup($profilepermissionBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_PROFILEPERMISSION_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_PROFILEPERMISSION_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PROFILEPERMISSION_DELETE");
            return false;
        }
    }

    // ---- others
    function updateProfilepermissions($id_profile, $permissionsIn) {
        $this->LOG_FUNCTION = "updateProfilepermissions";
        try {
            parent::startTransaction();

            if (empty($id_profile) || empty($permissionsIn)) {
                parent::rollbackTransaction();
                DelegateUtility::paramsNull($this, "ERROR_PROFILEPERMISSION_EDIT");
                return false;
            }

            $profileBS = new ProfileBS();
            $profile = $profileBS->unique($id_profile);

            // RIMUOVO TUTTI I PERMESSI
            $profilepermissionBS = new ProfilepermissionBS();
            $profilepermissionBS->cleanProfilepermissions($id_profile);

            // SALVO I PERMISSIONS
            $permissionUI = new PermissionUI();
            $permissions = DelegateUtility::mapEntityListByJson(new PermissionBS(), $permissionsIn, $permissionUI->obj);

            if (!ArrayUtility::isEmpty($permissions)) {
                $permissionBS = null;
                $permission = null;
                $id_permission = null;
                $id_profilepermission = null;
                foreach ($permissions as $permissionObj) {
                    // ricavo l'id del permission
                    $permissionBS = new PermissionBS();
                    $permissionBS->acceptNull = true;
                    $permissionBS->addCondition("cod", $permissionObj['Permission']['cod']);
                    $permission = $permissionBS->unique($permissionObj['Permission']['id']);

                    if (empty($permission)) {
                        $permissionBS = new PermissionBS();
                        $id_permission = $permissionBS->save($permissionObj);
                        $permission = $permissionObj;
                    } else {
                        $id_permission = $permission['Permission']['id'];
                    }

                    // salvo l'associazione profile-permission
                    $profilepermissionBS = new ProfilepermissionBS();
                    $profilepermission = $profilepermissionBS->instance();
                    $profilepermission['Profilepermission']['profile'] = $id_profile;
                    $profilepermission['Profilepermission']['permission'] = $id_permission;
                    $profilepermission['Profilepermission']['cod'] = $profile['Profile']['cod'] . "_" . $permission['Permission']['cod'];
                    $profilepermissionSaveBS = new ProfilepermissionBS();
                    $id_profilepermission = $profilepermissionSaveBS->save($profilepermission);
                }
            }

            parent::commitTransaction();
            $this->ok(TranslatorUtility::__translate("INFO_PROFILEPERMISSION_EDIT", $this->localefile));
            return true;

        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PROFILEPERMISSION_EDIT");
            return false;
        }
    }
}
