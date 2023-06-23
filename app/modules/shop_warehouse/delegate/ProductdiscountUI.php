<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("ProductdiscountBS", "modules/shop_warehouse/business");
App::uses("FileUtility", "modules/coreutils/utility");

class ProductdiscountUI extends AppGenericUI {

    function __construct() {
        parent::__construct("ProductdiscountUI");
        $this->localefile = "productdiscount";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("product", null, 0),
            new ObjPropertyEntity("discount", null, 0),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_PRODUCTDISCOUNT_NOT_FOUND");
                return "";
            }
            $productdiscountBS = new ProductdiscountBS();
            $productdiscountBS->json = $this->json;
            parent::completeByJsonFkVf($productdiscountBS);
            if (!empty($cod)) {
                $productdiscountBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $productdiscountBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_PRODUCTDISCOUNT_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $productdiscountBS = !empty($bs) ? $bs : new ProductdiscountBS();
            $productdiscountBS->json = $this->json;
            parent::completeByJsonFkVf($productdiscountBS);
            parent::evalConditions($productdiscountBS, $conditions);
            parent::evalOrders($productdiscountBS, $orders);
            $productdiscounts = $productdiscountBS->table($conditions, $orders, $paginate);
            parent::evalPagination($productdiscountBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($productdiscounts);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($productdiscountIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $productdiscount = DelegateUtility::getEntityToSave(new ProductdiscountBS(), $productdiscountIn, $this->obj);

            if (!empty($productdiscount)) {

                $productdiscountBS = new ProductdiscountBS();
                $id_productdiscount = $productdiscountBS->save($productdiscount);
                parent::saveInGroup($productdiscountBS, $id_productdiscount);

                parent::commitTransaction();
                if (!empty($id_productdiscount)) {
                    $this->ok(TranslatorUtility::__translate("INFO_PRODUCTDISCOUNT_SAVE", $this->localefile));
                    return $id_productdiscount;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_PRODUCTDISCOUNT_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_PRODUCTDISCOUNT_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PRODUCTDISCOUNT_SAVE");
            return 0;
        }
    }

    function edit($id, $productdiscountIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $productdiscount = DelegateUtility::getEntityToEdit(new ProductdiscountBS(), $productdiscountIn, $this->obj, $id);

            if (!empty($productdiscount)) {
                $productdiscountBS = new ProductdiscountBS();
                $id_productdiscount = $productdiscountBS->save($productdiscount);
                parent::saveInGroup($productdiscountBS, $id_productdiscount);
                parent::delInGroup($productdiscountBS, $id_productdiscount);

                parent::commitTransaction();
                if (!empty($id_productdiscount)) {
                    $this->ok(TranslatorUtility::__translate("INFO_PRODUCTDISCOUNT_EDIT", $this->localefile));
                    return $id_productdiscount;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_PRODUCTDISCOUNT_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_PRODUCTDISCOUNT_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PRODUCTDISCOUNT_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $productdiscountBS = new ProductdiscountBS();
                $productdiscountBS->delete($id);
                parent::delInGroup($productdiscountBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_PRODUCTDISCOUNT_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_PRODUCTDISCOUNT_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PRODUCTDISCOUNT_DELETE");
            return false;
        }
    }
}
