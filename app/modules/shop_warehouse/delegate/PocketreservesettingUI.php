<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("PocketreservesettingBS", "modules/shop_warehouse/business");
App::uses("FileUtility", "modules/coreutils/utility");

class PocketreservesettingUI extends AppGenericUI {

    function __construct() {
        parent::__construct("PocketreservesettingUI");
        $this->localefile = "pocketreservesetting";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("pocket", null, 0),
            new ObjPropertyEntity("settings", null, 0),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_POCKETRESERVESETTING_NOT_FOUND");
                return "";
            }
            $pocketreservesettingBS = new PocketreservesettingBS();
            $pocketreservesettingBS->json = $this->json;
            parent::completeByJsonFkVf($pocketreservesettingBS);
            if (!empty($cod)) {
                $pocketreservesettingBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $pocketreservesettingBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_POCKETRESERVESETTING_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $pocketreservesettingBS = !empty($bs) ? $bs : new PocketreservesettingBS();
            $pocketreservesettingBS->json = $this->json;
            parent::completeByJsonFkVf($pocketreservesettingBS);
            parent::evalConditions($pocketreservesettingBS, $conditions);
            parent::evalOrders($pocketreservesettingBS, $orders);
            $pocketreservesettings = $pocketreservesettingBS->table($conditions, $orders, $paginate);
            parent::evalPagination($pocketreservesettingBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($pocketreservesettings);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($pocketreservesettingIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $pocketreservesetting = DelegateUtility::getEntityToSave(new PocketreservesettingBS(), $pocketreservesettingIn, $this->obj);

            if (!empty($pocketreservesetting)) {

                $pocketreservesettingBS = new PocketreservesettingBS();
                $id_pocketreservesetting = $pocketreservesettingBS->save($pocketreservesetting);
                parent::saveInGroup($pocketreservesettingBS, $id_pocketreservesetting);

                parent::commitTransaction();
                if (!empty($id_pocketreservesetting)) {
                    $this->ok(TranslatorUtility::__translate("INFO_POCKETRESERVESETTING_SAVE", $this->localefile));
                    return $id_pocketreservesetting;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_POCKETRESERVESETTING_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_POCKETRESERVESETTING_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_POCKETRESERVESETTING_SAVE");
            return 0;
        }
    }

    function edit($id, $pocketreservesettingIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $pocketreservesetting = DelegateUtility::getEntityToEdit(new PocketreservesettingBS(), $pocketreservesettingIn, $this->obj, $id);

            if (!empty($pocketreservesetting)) {
                $pocketreservesettingBS = new PocketreservesettingBS();
                $id_pocketreservesetting = $pocketreservesettingBS->save($pocketreservesetting);
                parent::saveInGroup($pocketreservesettingBS, $id_pocketreservesetting);
                parent::delInGroup($pocketreservesettingBS, $id_pocketreservesetting);

                parent::commitTransaction();
                if (!empty($id_pocketreservesetting)) {
                    $this->ok(TranslatorUtility::__translate("INFO_POCKETRESERVESETTING_EDIT", $this->localefile));
                    return $id_pocketreservesetting;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_POCKETRESERVESETTING_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_POCKETRESERVESETTING_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_POCKETRESERVESETTING_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $pocketreservesettingBS = new PocketreservesettingBS();
                $pocketreservesettingBS->delete($id);
                parent::delInGroup($pocketreservesettingBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_POCKETRESERVESETTING_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_POCKETRESERVESETTING_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_POCKETRESERVESETTING_DELETE");
            return false;
        }
    }
}
