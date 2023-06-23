<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("ProfileBS", "modules/authentication/business");
App::uses("FileUtility", "modules/coreutils/utility");

class ProfileUI extends AppGenericUI {

    function __construct() {
        parent::__construct("ProfileUI");
        $this->localefile = "profile";
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
                DelegateUtility::paramsNull($this, "ERROR_PROFILE_NOT_FOUND");
                return "";
            }
            $profileBS = new ProfileBS();
            $profileBS->json = $this->json;
            parent::completeByJsonFkVf($profileBS);
            if (!empty($cod)) {
                $profileBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $profileBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_PROFILE_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $profileBS = !empty($bs) ? $bs : new ProfileBS();
            $profileBS->json = $this->json;
            parent::completeByJsonFkVf($profileBS);
            parent::evalConditions($profileBS, $conditions);
            parent::evalOrders($profileBS, $orders);
            $profiles = $profileBS->table($conditions, $orders, $paginate);
            parent::evalPagination($profileBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($profiles);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($profileIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $profile = DelegateUtility::getEntityToSave(new ProfileBS(), $profileIn, $this->obj);

            if (!empty($profile)) {

                $profileBS = new ProfileBS();
                $id_profile = $profileBS->save($profile);
                parent::saveInGroup($profileBS, $id_profile);

                parent::commitTransaction();
                if (!empty($id_profile)) {
                    $this->ok(TranslatorUtility::__translate("INFO_PROFILE_SAVE", $this->localefile));
                    return $id_profile;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_PROFILE_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_PROFILE_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PROFILE_SAVE");
            return 0;
        }
    }

    function edit($id, $profileIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $profile = DelegateUtility::getEntityToEdit(new ProfileBS(), $profileIn, $this->obj, $id);

            if (!empty($profile)) {
                $profileBS = new ProfileBS();
                $id_profile = $profileBS->save($profile);
                parent::saveInGroup($profileBS, $id_profile);
                parent::delInGroup($profileBS, $id_profile);

                parent::commitTransaction();
                if (!empty($id_profile)) {
                    $this->ok(TranslatorUtility::__translate("INFO_PROFILE_EDIT", $this->localefile));
                    return $id_profile;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_PROFILE_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_PROFILE_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PROFILE_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $profileBS = new ProfileBS();
                $profileBS->delete($id);
                parent::delInGroup($profileBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_PROFILE_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_PROFILE_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PROFILE_DELETE");
            return false;
        }
    }
}
