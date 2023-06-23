<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("PocketdiscountBS", "modules/shop_warehouse/business");
App::uses("FileUtility", "modules/coreutils/utility");

class PocketdiscountUI extends AppGenericUI {

    function __construct() {
        parent::__construct("PocketdiscountUI");
        $this->localefile = "pocketdiscount";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("pocket", null, 0),
            new ObjPropertyEntity("discount", null, 0),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_POCKETDISCOUNT_NOT_FOUND");
                return "";
            }
            $pocketdiscountBS = new PocketdiscountBS();
            $pocketdiscountBS->json = $this->json;
            parent::completeByJsonFkVf($pocketdiscountBS);
            if (!empty($cod)) {
                $pocketdiscountBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $pocketdiscountBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_POCKETDISCOUNT_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $pocketdiscountBS = !empty($bs) ? $bs : new PocketdiscountBS();
            $pocketdiscountBS->json = $this->json;
            parent::completeByJsonFkVf($pocketdiscountBS);
            parent::evalConditions($pocketdiscountBS, $conditions);
            parent::evalOrders($pocketdiscountBS, $orders);
            $pocketdiscounts = $pocketdiscountBS->table($conditions, $orders, $paginate);
            parent::evalPagination($pocketdiscountBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($pocketdiscounts);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($pocketdiscountIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $pocketdiscount = DelegateUtility::getEntityToSave(new PocketdiscountBS(), $pocketdiscountIn, $this->obj);

            if (!empty($pocketdiscount)) {

                $pocketdiscountBS = new PocketdiscountBS();
                $id_pocketdiscount = $pocketdiscountBS->save($pocketdiscount);
                parent::saveInGroup($pocketdiscountBS, $id_pocketdiscount);

                parent::commitTransaction();
                if (!empty($id_pocketdiscount)) {
                    $this->ok(TranslatorUtility::__translate("INFO_POCKETDISCOUNT_SAVE", $this->localefile));
                    return $id_pocketdiscount;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_POCKETDISCOUNT_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_POCKETDISCOUNT_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_POCKETDISCOUNT_SAVE");
            return 0;
        }
    }

    function edit($id, $pocketdiscountIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $pocketdiscount = DelegateUtility::getEntityToEdit(new PocketdiscountBS(), $pocketdiscountIn, $this->obj, $id);

            if (!empty($pocketdiscount)) {
                $pocketdiscountBS = new PocketdiscountBS();
                $id_pocketdiscount = $pocketdiscountBS->save($pocketdiscount);
                parent::saveInGroup($pocketdiscountBS, $id_pocketdiscount);
                parent::delInGroup($pocketdiscountBS, $id_pocketdiscount);

                parent::commitTransaction();
                if (!empty($id_pocketdiscount)) {
                    $this->ok(TranslatorUtility::__translate("INFO_POCKETDISCOUNT_EDIT", $this->localefile));
                    return $id_pocketdiscount;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_POCKETDISCOUNT_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_POCKETDISCOUNT_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_POCKETDISCOUNT_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $pocketdiscountBS = new PocketdiscountBS();
                $pocketdiscountBS->delete($id);
                parent::delInGroup($pocketdiscountBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_POCKETDISCOUNT_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_POCKETDISCOUNT_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_POCKETDISCOUNT_DELETE");
            return false;
        }
    }
}
