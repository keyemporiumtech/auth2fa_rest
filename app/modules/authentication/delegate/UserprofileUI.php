<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("UserprofileBS", "modules/authentication/business");
App::uses("FileUtility", "modules/coreutils/utility");
App::uses("ArrayUtility", "modules/coreutils/utility");
App::uses("ProfileUI", "modules/authentication/delegate");
App::uses("ProfileBS", "modules/authentication/business");
App::uses("ProfilepermissionUI", "modules/authentication/delegate");
App::uses("ProfilepermissionBS", "modules/authentication/business");
App::uses("UserBS", "modules/authentication/business");

class UserprofileUI extends AppGenericUI {

    function __construct() {
        parent::__construct("UserprofileUI");
        $this->localefile = "userprofile";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("user", null, 0),
            new ObjPropertyEntity("profile", null, 0),
            new ObjPropertyEntity("flgdefault", null, false),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_USERPROFILE_NOT_FOUND");
                return "";
            }
            $userprofileBS = new UserprofileBS();
            $userprofileBS->json = $this->json;
            parent::completeByJsonFkVf($userprofileBS);
            if (!empty($cod)) {
                $userprofileBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $userprofileBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_USERPROFILE_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $userprofileBS = !empty($bs) ? $bs : new UserprofileBS();
            $userprofileBS->json = $this->json;
            parent::completeByJsonFkVf($userprofileBS);
            parent::evalConditions($userprofileBS, $conditions);
            parent::evalOrders($userprofileBS, $orders);
            $userprofiles = $userprofileBS->table($conditions, $orders, $paginate);
            parent::evalPagination($userprofileBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($userprofiles);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($userprofileIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $userprofile = DelegateUtility::getEntityToSave(new UserprofileBS(), $userprofileIn, $this->obj);

            if (!empty($userprofile)) {

                $userprofileBS = new UserprofileBS();
                $id_userprofile = $userprofileBS->save($userprofile);
                parent::saveInGroup($userprofileBS, $id_userprofile);

                parent::commitTransaction();
                if (!empty($id_userprofile)) {
                    $this->ok(TranslatorUtility::__translate("INFO_USERPROFILE_SAVE", $this->localefile));
                    return $id_userprofile;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_USERPROFILE_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_USERPROFILE_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_USERPROFILE_SAVE");
            return 0;
        }
    }

    function edit($id, $userprofileIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $userprofile = DelegateUtility::getEntityToEdit(new UserprofileBS(), $userprofileIn, $this->obj, $id);

            if (!empty($userprofile)) {
                $userprofileBS = new UserprofileBS();
                $id_userprofile = $userprofileBS->save($userprofile);
                parent::saveInGroup($userprofileBS, $id_userprofile);
                parent::delInGroup($userprofileBS, $id_userprofile);

                parent::commitTransaction();
                if (!empty($id_userprofile)) {
                    $this->ok(TranslatorUtility::__translate("INFO_USERPROFILE_EDIT", $this->localefile));
                    return $id_userprofile;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_USERPROFILE_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_USERPROFILE_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_USERPROFILE_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $userprofileBS = new UserprofileBS();
                $userprofileBS->delete($id);
                parent::delInGroup($userprofileBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_USERPROFILE_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_USERPROFILE_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_USERPROFILE_DELETE");
            return false;
        }
    }

    /* profiles and permissions */
    function createProfile($profileIn, $permissionsIn, $id_user = null) {
        $this->LOG_FUNCTION = "createProfile";
        try {
            parent::startTransaction();

            if (empty($profileIn) || empty($permissionsIn)) {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_USERPROFILE_SAVE");
                return 0;
            }

            $profileUI = new ProfileUI();
            $profileUI->json = $this->json;
            $profileUI->transactional = true;
            $id_profile = $profileUI->save($profileIn);
            if (empty($id_profile)) {
                parent::rollbackTransaction();
                parent::mappingDelegate($profileUI);
                return 0;
            }
            $profile = DelegateUtility::mapEntityByJson(new ProfileBS(), $profileIn, $profileUI->obj);

            // SALVO I PERMISSIONS
            $profilepermissionUI = new ProfilepermissionUI();
            $profilepermissionUI->transactional = true;
            $flag = $profilepermissionUI->updateProfilepermissions($id_profile, $permissionsIn);
            if (!$flag) {
                parent::rollbackTransaction();
                parent::mappingDelegate($profilepermissionUI);
                return 0;
            }

            // SALVO L'ASSOCIAZIONE user-profile
            if (!empty($id_user)) {
                $userBS = new UserBS();
                $user = $userBS->unique($id_user);
                $userprofileBS = new UserprofileBS();
                $userprofile = $userprofileBS->instance();
                $userprofile['Userprofile']['user'] = $id_user;
                $userprofile['Userprofile']['profile'] = $id_profile;
                $userprofile['Userprofile']['cod'] = $user['User']['username'] . "_" . $profile['Profile']['cod'];
                $id_userprofile = $userprofileBS->save($userprofile);
                if (empty($id_userprofile)) {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_USERPROFILE_SAVE");
                    return 0;
                }
            }

            // ritorno l'id del profilo creato
            parent::commitTransaction();
            $this->ok(TranslatorUtility::__translate("INFO_USERPROFILE_SAVE", $this->localefile));
            return $id_profile;

        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_USERPROFILE_SAVE");
            return 0;
        }
    }

    function removeProfile($id_profile, $restrict=false, $id_user = null) {
        $this->LOG_FUNCTION = "removeProfile";
        try {
            parent::startTransaction();

            if (empty($id_profile)) {
                parent::rollbackTransaction();
                DelegateUtility::paramsNull($this, "ERROR_USERPROFILE_DELETE");
                return false;
            }

            // RIMUOVO I PERMISSIONS
            $profilepermissionBS = new ProfilepermissionBS();
            $profilepermissionBS->cleanProfilepermissions($id_profile);

            // RIMUOVO L'ASSOCIAZIONE user-profile
            if (!empty($id_user) && !$restrict) {
                $userprofileBS = new UserprofileBS();
                $userprofileBS->addCondition("profile", $id_profile);
                $userprofileBS->addCondition("user", $id_user);
                $userprofile = $userprofileBS->unique();

                $userprofileBS = new UserprofileBS();
                $userprofileBS->delete($userprofile['Userprofile']['id']);
            } else {
                $userprofileBS = new UserprofileBS();
                $userprofileBS->cleanProfile($id_profile);
            }

            if($restrict){
                $profileBS = new ProfileBS();
                $profileBS->delete($id_profile);
            }

            parent::commitTransaction();
            $this->ok(TranslatorUtility::__translate("INFO_USERPROFILE_DELETE", $this->localefile));
            return true;

        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_USERPROFILE_DELETE");
            return false;
        }
    }
}
