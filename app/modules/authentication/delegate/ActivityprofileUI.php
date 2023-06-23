<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("ActivityprofileBS", "modules/authentication/business");
App::uses("UserprofileBS", "modules/authentication/business");
App::uses("FileUtility", "modules/coreutils/utility");
App::uses("ArrayUtility", "modules/coreutils/utility");
App::uses("ProfileUI", "modules/authentication/delegate");
App::uses("ProfileBS", "modules/authentication/business");
App::uses("ProfilepermissionUI", "modules/authentication/delegate");
App::uses("ProfilepermissionBS", "modules/authentication/business");
App::uses("UserBS", "modules/authentication/business");
App::uses("ActivityBS", "modules/authentication/business");

class ActivityprofileUI extends AppGenericUI {

    function __construct() {
        parent::__construct("ActivityprofileUI");
        $this->localefile = "activityprofile";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("activity", null, 0),
            new ObjPropertyEntity("profile", null, 0),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_ACTIVITYPROFILE_NOT_FOUND");
                return "";
            }
            $activityprofileBS = new ActivityprofileBS();
            $activityprofileBS->json = $this->json;
            parent::completeByJsonFkVf($activityprofileBS);
            if (!empty($cod)) {
                $activityprofileBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $activityprofileBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_ACTIVITYPROFILE_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $activityprofileBS = !empty($bs) ? $bs : new ActivityprofileBS();
            $activityprofileBS->json = $this->json;
            parent::completeByJsonFkVf($activityprofileBS);
            parent::evalConditions($activityprofileBS, $conditions);
            parent::evalOrders($activityprofileBS, $orders);
            $activityprofiles = $activityprofileBS->table($conditions, $orders, $paginate);
            parent::evalPagination($activityprofileBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($activityprofiles);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($activityprofileIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $activityprofile = DelegateUtility::getEntityToSave(new ActivityprofileBS(), $activityprofileIn, $this->obj);

            if (!empty($activityprofile)) {

                $activityprofileBS = new ActivityprofileBS();
                $id_activityprofile = $activityprofileBS->save($activityprofile);
                parent::saveInGroup($activityprofileBS, $id_activityprofile);

                parent::commitTransaction();
                if (!empty($id_activityprofile)) {
                    $this->ok(TranslatorUtility::__translate("INFO_ACTIVITYPROFILE_SAVE", $this->localefile));
                    return $id_activityprofile;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_ACTIVITYPROFILE_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_ACTIVITYPROFILE_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_ACTIVITYPROFILE_SAVE");
            return 0;
        }
    }

    function edit($id, $activityprofileIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $activityprofile = DelegateUtility::getEntityToEdit(new ActivityprofileBS(), $activityprofileIn, $this->obj, $id);

            if (!empty($activityprofile)) {
                $activityprofileBS = new ActivityprofileBS();
                $id_activityprofile = $activityprofileBS->save($activityprofile);
                parent::saveInGroup($activityprofileBS, $id_activityprofile);
                parent::delInGroup($activityprofileBS, $id_activityprofile);

                parent::commitTransaction();
                if (!empty($id_activityprofile)) {
                    $this->ok(TranslatorUtility::__translate("INFO_ACTIVITYPROFILE_EDIT", $this->localefile));
                    return $id_activityprofile;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_ACTIVITYPROFILE_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_ACTIVITYPROFILE_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_ACTIVITYPROFILE_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $activityprofileBS = new ActivityprofileBS();
                $activityprofileBS->delete($id);
                parent::delInGroup($activityprofileBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_ACTIVITYPROFILE_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_ACTIVITYPROFILE_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_ACTIVITYPROFILE_DELETE");
            return false;
        }
    }

    /* profiles and permissions */
    function createProfile($profileIn, $id_activity, $permissionsIn, $id_user = null) {
        $this->LOG_FUNCTION = "createProfile";
        try {
            parent::startTransaction();

            if (empty($profileIn) || empty($permissionsIn)) {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_ACTIVITYPROFILE_SAVE");
                return 0;
            }

            if (empty($id_activity)) {
                parent::rollbackTransaction();
                DelegateUtility::paramsNull($this, "ERROR_ACTIVITYPROFILE_SAVE");
                return 0;
            }

            $activityBS = new ActivityBS();
            $activity = $activityBS->unique($id_activity);

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
                $userprofile['Userprofile']['activity'] = $id_activity;
                $userprofile['Userprofile']['profile'] = $id_profile;
                $userprofile['Userprofile']['cod'] = $user['User']['username'] . "_" . $profile['Profile']['cod'] . "_" . $activity['Activity']['piva'];
                $id_userprofile = $userprofileBS->save($userprofile);
                if (empty($id_userprofile)) {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_ACTIVITYPROFILE_SAVE");
                    return 0;
                }
            }

            // SALVO L'ASSOCIAZIONE activity-profile
            $activityprofileBS = new ActivityprofileBS();
            $activityprofile = $activityprofileBS->instance();
            $activityprofile['Activityprofile']['activity'] = $id_activity;
            $activityprofile['Activityprofile']['profile'] = $id_profile;

            $activityprofileSaveBS = new ActivityprofileBS();
            $id_activityprofile = $activityprofileSaveBS->save($activityprofile);

            if (empty($id_activityprofile)) {
                parent::rollbackTransaction();
                DelegateUtility::nonSalvato($this, "ERROR_ACTIVITYPROFILE_SAVE");
                return 0;
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

    function removeProfile($id_profile, $id_activity, $restrict = false, $id_user = null) {
        $this->LOG_FUNCTION = "removeProfile";
        try {
            parent::startTransaction();

            if (empty($id_profile) || empty($id_activity)) {
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

            if (!$restrict) {
                // RIMUOVO L'ASSOCIAZIONE activity-profile
                $activityprofileBS = new ActivityprofileBS();
                $activityprofileBS->addCondition("activity", $id_activity);
                $activityprofileBS->addCondition("profile", $id_profile);
                $activityprofile = $activityprofileBS->unique();

                $activityprofileDeleteBS = new ActivityprofileBS();
                $activityprofileDeleteBS->delete($activityprofile['Activityprofile']['id']);
            } else {
                $activityprofileBS = new ActivityprofileBS();
                $activityprofileBS->cleanProfile($id_profile);

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
