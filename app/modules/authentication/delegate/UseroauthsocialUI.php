<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("UseroauthsocialBS", "modules/authentication/business");
App::uses("FileUtility", "modules/coreutils/utility");

class UseroauthsocialUI extends AppGenericUI {

    function __construct() {
        parent::__construct("UseroauthsocialUI");
        $this->localefile = "useroauthsocial";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("oauthid", null, ""),
            new ObjPropertyEntity("tpsocialreference", null, 0),
            new ObjPropertyEntity("user", null, 0),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_USEROAUTHSOCIAL_NOT_FOUND");
                return "";
            }
            $useroauthsocialBS = new UseroauthsocialBS();
            $useroauthsocialBS->json = $this->json;
            parent::completeByJsonFkVf($useroauthsocialBS);
            if (!empty($cod)) {
                $useroauthsocialBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $useroauthsocialBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_USEROAUTHSOCIAL_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $useroauthsocialBS = !empty($bs) ? $bs : new UseroauthsocialBS();
            $useroauthsocialBS->json = $this->json;
            parent::completeByJsonFkVf($useroauthsocialBS);
            parent::evalConditions($useroauthsocialBS, $conditions);
            parent::evalOrders($useroauthsocialBS, $orders);
            $useroauthsocials = $useroauthsocialBS->table($conditions, $orders, $paginate);
            parent::evalPagination($useroauthsocialBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($useroauthsocials);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($useroauthsocialIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $useroauthsocial = DelegateUtility::getEntityToSave(new UseroauthsocialBS(), $useroauthsocialIn, $this->obj);

            if (!empty($useroauthsocial)) {

                $useroauthsocialBS = new UseroauthsocialBS();
                $id_useroauthsocial = $useroauthsocialBS->save($useroauthsocial);
                parent::saveInGroup($useroauthsocialBS, $id_useroauthsocial);

                parent::commitTransaction();
                if (!empty($id_useroauthsocial)) {
                    $this->ok(TranslatorUtility::__translate("INFO_USEROAUTHSOCIAL_SAVE", $this->localefile));
                    return $id_useroauthsocial;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_USEROAUTHSOCIAL_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_USEROAUTHSOCIAL_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_USEROAUTHSOCIAL_SAVE");
            return 0;
        }
    }

    function edit($id, $useroauthsocialIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $useroauthsocial = DelegateUtility::getEntityToEdit(new UseroauthsocialBS(), $useroauthsocialIn, $this->obj, $id);

            if (!empty($useroauthsocial)) {
                $useroauthsocialBS = new UseroauthsocialBS();
                $id_useroauthsocial = $useroauthsocialBS->save($useroauthsocial);
                parent::saveInGroup($useroauthsocialBS, $id_useroauthsocial);
                parent::delInGroup($useroauthsocialBS, $id_useroauthsocial);

                parent::commitTransaction();
                if (!empty($id_useroauthsocial)) {
                    $this->ok(TranslatorUtility::__translate("INFO_USEROAUTHSOCIAL_EDIT", $this->localefile));
                    return $id_useroauthsocial;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_USEROAUTHSOCIAL_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_USEROAUTHSOCIAL_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_USEROAUTHSOCIAL_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $useroauthsocialBS = new UseroauthsocialBS();
                $useroauthsocialBS->delete($id);
                parent::delInGroup($useroauthsocialBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_USEROAUTHSOCIAL_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_USEROAUTHSOCIAL_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_USEROAUTHSOCIAL_DELETE");
            return false;
        }
    }

    /************* OTHERS ****************/
    function getByOauth($oauthid = null, $id_user = null, $tpsocialreference = null) {
        $this->LOG_FUNCTION = "getByOauth";
        try {
            if (empty($oauthid)) {
                DelegateUtility::paramsNull($this, "ERROR_USEROAUTHSOCIAL_NOT_FOUND");
                return "";
            }
            $useroauthsocialBS = new UseroauthsocialBS();
            $useroauthsocialBS->json = $this->json;
            if (!empty($id_user)) {
                $useroauthsocialBS->addCondition("user", $id_user);
            }
            if (!empty($tpsocialreference)) {
                $useroauthsocialBS->addCondition("tpsocialreference", $tpsocialreference);
            }
            $useroauthsocialBS->addCondition("oauthid", $oauthid);

            $this->ok();
            return $useroauthsocialBS->unique();
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_USEROAUTHSOCIAL_NOT_FOUND");
            return "";
        }
    }
}
