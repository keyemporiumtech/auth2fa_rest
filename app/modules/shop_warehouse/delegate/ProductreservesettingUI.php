<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("ProductreservesettingBS", "modules/shop_warehouse/business");
App::uses("FileUtility", "modules/coreutils/utility");

class ProductreservesettingUI extends AppGenericUI {

    function __construct() {
        parent::__construct("ProductreservesettingUI");
        $this->localefile = "productreservesetting";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("product", null, 0),
            new ObjPropertyEntity("settings", null, 0),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_PRODUCTRESERVATIONSETTING_NOT_FOUND");
                return "";
            }
            $productreservesettingBS = new ProductreservesettingBS();
            $productreservesettingBS->json = $this->json;
            parent::completeByJsonFkVf($productreservesettingBS);
            if (!empty($cod)) {
                $productreservesettingBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $productreservesettingBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_PRODUCTRESERVATIONSETTING_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $productreservesettingBS = !empty($bs) ? $bs : new ProductreservesettingBS();
            $productreservesettingBS->json = $this->json;
            parent::completeByJsonFkVf($productreservesettingBS);
            parent::evalConditions($productreservesettingBS, $conditions);
            parent::evalOrders($productreservesettingBS, $orders);
            $productreservesettings = $productreservesettingBS->table($conditions, $orders, $paginate);
            parent::evalPagination($productreservesettingBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($productreservesettings);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($productreservesettingIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $productreservesetting = DelegateUtility::getEntityToSave(new ProductreservesettingBS(), $productreservesettingIn, $this->obj);

            if (!empty($productreservesetting)) {

                $productreservesettingBS = new ProductreservesettingBS();
                $id_productreservesetting = $productreservesettingBS->save($productreservesetting);
                parent::saveInGroup($productreservesettingBS, $id_productreservesetting);

                parent::commitTransaction();
                if (!empty($id_productreservesetting)) {
                    $this->ok(TranslatorUtility::__translate("INFO_PRODUCTRESERVATIONSETTING_SAVE", $this->localefile));
                    return $id_productreservesetting;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_PRODUCTRESERVATIONSETTING_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_PRODUCTRESERVATIONSETTING_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PRODUCTRESERVATIONSETTING_SAVE");
            return 0;
        }
    }

    function edit($id, $productreservesettingIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $productreservesetting = DelegateUtility::getEntityToEdit(new ProductreservesettingBS(), $productreservesettingIn, $this->obj, $id);

            if (!empty($productreservesetting)) {
                $productreservesettingBS = new ProductreservesettingBS();
                $id_productreservesetting = $productreservesettingBS->save($productreservesetting);
                parent::saveInGroup($productreservesettingBS, $id_productreservesetting);
                parent::delInGroup($productreservesettingBS, $id_productreservesetting);

                parent::commitTransaction();
                if (!empty($id_productreservesetting)) {
                    $this->ok(TranslatorUtility::__translate("INFO_PRODUCTRESERVATIONSETTING_EDIT", $this->localefile));
                    return $id_productreservesetting;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_PRODUCTRESERVATIONSETTING_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_PRODUCTRESERVATIONSETTING_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PRODUCTRESERVATIONSETTING_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $productreservesettingBS = new ProductreservesettingBS();
                $productreservesettingBS->delete($id);
                parent::delInGroup($productreservesettingBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_PRODUCTRESERVATIONSETTING_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_PRODUCTRESERVATIONSETTING_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PRODUCTRESERVATIONSETTING_DELETE");
            return false;
        }
    }
}
