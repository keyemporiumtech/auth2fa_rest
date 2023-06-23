<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("PriceBS", "modules/shop_warehouse/business");
App::uses("FileUtility", "modules/coreutils/utility");
App::uses("CurrencyUtility", "modules/util_currency/utility");

class PriceUI extends AppGenericUI {

    function __construct() {
        parent::__construct("PriceUI");
        $this->localefile = "price";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("price", null, 0.00),
            new ObjPropertyEntity("total", null, 0.00),
            new ObjPropertyEntity("iva", null, 0.00),
            new ObjPropertyEntity("iva_percent", null, 0.00),
            new ObjPropertyEntity("discount", null, 0.00),
            new ObjPropertyEntity("discount_percent", null, 0.00),
            new ObjPropertyEntity("tax", null, 0.00),
            new ObjPropertyEntity("currencyid", null, CurrencyUtility::getCurrencySystem()['Currency']['id']),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_PRICE_NOT_FOUND");
                return "";
            }
            $priceBS = new PriceBS();
            $priceBS->json = $this->json;
            parent::completeByJsonFkVf($priceBS);
            if (!empty($cod)) {
                $priceBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $priceBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_PRICE_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $priceBS = !empty($bs) ? $bs : new PriceBS();
            $priceBS->json = $this->json;
            parent::completeByJsonFkVf($priceBS);
            parent::evalConditions($priceBS, $conditions);
            parent::evalOrders($priceBS, $orders);
            $prices = $priceBS->table($conditions, $orders, $paginate);
            parent::evalPagination($priceBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($prices);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($priceIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $price = DelegateUtility::getEntityToSave(new PriceBS(), $priceIn, $this->obj);

            if (!empty($price)) {

                $priceBS = new PriceBS();
                $id_price = $priceBS->save($price);
                parent::saveInGroup($priceBS, $id_price);

                parent::commitTransaction();
                if (!empty($id_price)) {
                    $this->ok(TranslatorUtility::__translate("INFO_PRICE_SAVE", $this->localefile));
                    return $id_price;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_PRICE_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_PRICE_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PRICE_SAVE");
            return 0;
        }
    }

    function edit($id, $priceIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $price = DelegateUtility::getEntityToEdit(new PriceBS(), $priceIn, $this->obj, $id);

            if (!empty($price)) {
                $priceBS = new PriceBS();
                $id_price = $priceBS->save($price);
                parent::saveInGroup($priceBS, $id_price);
                parent::delInGroup($priceBS, $id_price);

                parent::commitTransaction();
                if (!empty($id_price)) {
                    $this->ok(TranslatorUtility::__translate("INFO_PRICE_EDIT", $this->localefile));
                    return $id_price;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_PRICE_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_PRICE_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PRICE_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $priceBS = new PriceBS();
                $priceBS->delete($id);
                parent::delInGroup($priceBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_PRICE_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_PRICE_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PRICE_DELETE");
            return false;
        }
    }

    // ------------- CALC
    function calcIva($total = null, $iva = null, $iva_percent = null, $flg_ivainclude = null) {
        $this->LOG_FUNCTION = "calcIva";
        try {
            if (empty($total) && (empty($iva) || empty($iva_percent))) {
                DelegateUtility::paramsNull($this, "ERROR_PRICE_CALC");
                return "";
            }
            $result = null;
            if (empty($iva) && !empty($iva_percent)) {
                $result = PriceUtility::calcIva($total, $iva_percent, $flg_ivainclude);
            } elseif (!empty($iva) && empty($iva_percent)) {
                $result = PriceUtility::calcIvaPercent($total, $iva, $flg_ivainclude);
            }
            return $this->json ? json_encode($result) : $result;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PRICE_CALC");
            return false;
        }
    }
}
