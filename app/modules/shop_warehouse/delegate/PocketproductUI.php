<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("PocketproductBS", "modules/shop_warehouse/business");
App::uses("FileUtility", "modules/coreutils/utility");

class PocketproductUI extends AppGenericUI {

    function __construct() {
        parent::__construct("PocketproductUI");
        $this->localefile = "pocketproduct";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("pocket", null, 0),
            new ObjPropertyEntity("product", null, 0),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_POCKETPRODUCT_NOT_FOUND");
                return "";
            }
            $pocketproductBS = new PocketproductBS();
            $pocketproductBS->json = $this->json;
            parent::completeByJsonFkVf($pocketproductBS);
            if (!empty($cod)) {
                $pocketproductBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $pocketproductBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_POCKETPRODUCT_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $pocketproductBS = !empty($bs) ? $bs : new PocketproductBS();
            $pocketproductBS->json = $this->json;
            parent::completeByJsonFkVf($pocketproductBS);
            parent::evalConditions($pocketproductBS, $conditions);
            parent::evalOrders($pocketproductBS, $orders);
            $pocketproducts = $pocketproductBS->table($conditions, $orders, $paginate);
            parent::evalPagination($pocketproductBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($pocketproducts);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($pocketproductIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $pocketproduct = DelegateUtility::getEntityToSave(new PocketproductBS(), $pocketproductIn, $this->obj);

            if (!empty($pocketproduct)) {

                $pocketproductBS = new PocketproductBS();
                $id_pocketproduct = $pocketproductBS->save($pocketproduct);
                parent::saveInGroup($pocketproductBS, $id_pocketproduct);

                parent::commitTransaction();
                if (!empty($id_pocketproduct)) {
                    $this->ok(TranslatorUtility::__translate("INFO_POCKETPRODUCT_SAVE", $this->localefile));
                    return $id_pocketproduct;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_POCKETPRODUCT_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_POCKETPRODUCT_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_POCKETPRODUCT_SAVE");
            return 0;
        }
    }

    function edit($id, $pocketproductIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $pocketproduct = DelegateUtility::getEntityToEdit(new PocketproductBS(), $pocketproductIn, $this->obj, $id);

            if (!empty($pocketproduct)) {
                $pocketproductBS = new PocketproductBS();
                $id_pocketproduct = $pocketproductBS->save($pocketproduct);
                parent::saveInGroup($pocketproductBS, $id_pocketproduct);
                parent::delInGroup($pocketproductBS, $id_pocketproduct);

                parent::commitTransaction();
                if (!empty($id_pocketproduct)) {
                    $this->ok(TranslatorUtility::__translate("INFO_POCKETPRODUCT_EDIT", $this->localefile));
                    return $id_pocketproduct;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_POCKETPRODUCT_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_POCKETPRODUCT_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_POCKETPRODUCT_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $pocketproductBS = new PocketproductBS();
                $pocketproductBS->delete($id);
                parent::delInGroup($pocketproductBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_POCKETPRODUCT_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_POCKETPRODUCT_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_POCKETPRODUCT_DELETE");
            return false;
        }
    }
}
