<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("ProductattachmentBS", "modules/shop_warehouse/business");
App::uses("FileUtility", "modules/coreutils/utility");

class ProductattachmentUI extends AppGenericUI {

    function __construct() {
        parent::__construct("ProductattachmentUI");
        $this->localefile = "productattachment";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("product", null, 0),
            new ObjPropertyEntity("attachment", null, 0),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_PRODUCTATTACHMENT_NOT_FOUND");
                return "";
            }
            $productattachmentBS = new ProductattachmentBS();
            $productattachmentBS->json = $this->json;
            parent::completeByJsonFkVf($productattachmentBS);
            if (!empty($cod)) {
                $productattachmentBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $productattachmentBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_PRODUCTATTACHMENT_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $productattachmentBS = !empty($bs) ? $bs : new ProductattachmentBS();
            $productattachmentBS->json = $this->json;
            parent::completeByJsonFkVf($productattachmentBS);
            parent::evalConditions($productattachmentBS, $conditions);
            parent::evalOrders($productattachmentBS, $orders);
            $productattachments = $productattachmentBS->table($conditions, $orders, $paginate);
            parent::evalPagination($productattachmentBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($productattachments);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($productattachmentIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $productattachment = DelegateUtility::getEntityToSave(new ProductattachmentBS(), $productattachmentIn, $this->obj);

            if (!empty($productattachment)) {

                $productattachmentBS = new ProductattachmentBS();
                $id_productattachment = $productattachmentBS->save($productattachment);
                parent::saveInGroup($productattachmentBS, $id_productattachment);

                parent::commitTransaction();
                if (!empty($id_productattachment)) {
                    $this->ok(TranslatorUtility::__translate("INFO_PRODUCTATTACHMENT_SAVE", $this->localefile));
                    return $id_productattachment;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_PRODUCTATTACHMENT_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_PRODUCTATTACHMENT_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PRODUCTATTACHMENT_SAVE");
            return 0;
        }
    }

    function edit($id, $productattachmentIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $productattachment = DelegateUtility::getEntityToEdit(new ProductattachmentBS(), $productattachmentIn, $this->obj, $id);

            if (!empty($productattachment)) {
                $productattachmentBS = new ProductattachmentBS();
                $id_productattachment = $productattachmentBS->save($productattachment);
                parent::saveInGroup($productattachmentBS, $id_productattachment);
                parent::delInGroup($productattachmentBS, $id_productattachment);

                parent::commitTransaction();
                if (!empty($id_productattachment)) {
                    $this->ok(TranslatorUtility::__translate("INFO_PRODUCTATTACHMENT_EDIT", $this->localefile));
                    return $id_productattachment;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_PRODUCTATTACHMENT_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_PRODUCTATTACHMENT_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PRODUCTATTACHMENT_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $productattachmentBS = new ProductattachmentBS();
                $productattachmentBS->delete($id);
                parent::delInGroup($productattachmentBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_PRODUCTATTACHMENT_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_PRODUCTATTACHMENT_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PRODUCTATTACHMENT_DELETE");
            return false;
        }
    }
}
