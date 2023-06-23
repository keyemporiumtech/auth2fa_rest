<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("DiscountBS", "modules/shop_warehouse/business");
App::uses("FileUtility", "modules/coreutils/utility");
App::uses("CurrencyUtility", "modules/util_currency/utility");

class DiscountUI extends AppGenericUI {

    function __construct() {
        parent::__construct("DiscountUI");
        $this->localefile = "discount";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("discount", null, 0.00),
            new ObjPropertyEntity("discount_percent", null, 0.00),
            new ObjPropertyEntity("description", null, ""),
            new ObjPropertyEntity("levelquantity", null, 0),
            new ObjPropertyEntity("levelprice", null, 0.00),
            new ObjPropertyEntity("dtainit", null, ""),
            new ObjPropertyEntity("dtaend", null, ""),
            new ObjPropertyEntity("flgsystem", null, 0),
            new ObjPropertyEntity("flglevelbasket", null, 0),
            new ObjPropertyEntity("currencyid", null, CurrencyUtility::getCurrencySystem()['Currency']['id']),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_DISCOUNT_NOT_FOUND");
                return "";
            }
            $discountBS = new DiscountBS();
            $discountBS->json = $this->json;
            parent::completeByJsonFkVf($discountBS);
            if (!empty($cod)) {
                $discountBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $discountBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_DISCOUNT_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $discountBS = !empty($bs) ? $bs : new DiscountBS();
            $discountBS->json = $this->json;
            parent::completeByJsonFkVf($discountBS);
            parent::evalConditions($discountBS, $conditions);
            parent::evalOrders($discountBS, $orders);
            $discounts = $discountBS->table($conditions, $orders, $paginate);
            parent::evalPagination($discountBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($discounts);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($discountIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $discount = DelegateUtility::getEntityToSave(new DiscountBS(), $discountIn, $this->obj);

            if (!empty($discount)) {

                $discountBS = new DiscountBS();
                $id_discount = $discountBS->save($discount);
                parent::saveInGroup($discountBS, $id_discount);

                parent::commitTransaction();
                if (!empty($id_discount)) {
                    $this->ok(TranslatorUtility::__translate("INFO_DISCOUNT_SAVE", $this->localefile));
                    return $id_discount;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_DISCOUNT_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_DISCOUNT_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_DISCOUNT_SAVE");
            return 0;
        }
    }

    function edit($id, $discountIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $discount = DelegateUtility::getEntityToEdit(new DiscountBS(), $discountIn, $this->obj, $id);

            if (!empty($discount)) {
                $discountBS = new DiscountBS();
                $id_discount = $discountBS->save($discount);
                parent::saveInGroup($discountBS, $id_discount);
                parent::delInGroup($discountBS, $id_discount);

                parent::commitTransaction();
                if (!empty($id_discount)) {
                    $this->ok(TranslatorUtility::__translate("INFO_DISCOUNT_EDIT", $this->localefile));
                    return $id_discount;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_DISCOUNT_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_DISCOUNT_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_DISCOUNT_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $discountBS = new DiscountBS();
                $discountBS->delete($id);
                parent::delInGroup($discountBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_DISCOUNT_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_DISCOUNT_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_DISCOUNT_DELETE");
            return false;
        }
    }
}
