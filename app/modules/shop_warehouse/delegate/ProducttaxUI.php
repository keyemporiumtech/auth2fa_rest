<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("ProducttaxBS", "modules/shop_warehouse/business");
App::uses("FileUtility", "modules/coreutils/utility");
App::uses("CurrencyUtility", "modules/util_currency/utility");

class ProducttaxUI extends AppGenericUI {

    function __construct() {
        parent::__construct("ProducttaxUI");
        $this->localefile = "producttax";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("product", null, 0),
            new ObjPropertyEntity("tax", null, 0.00),
            new ObjPropertyEntity("tax_percent", null, 0.00),
            new ObjPropertyEntity("taxdescription", null, ""),
            new ObjPropertyEntity("currencyid", null, CurrencyUtility::getCurrencySystem()['Currency']['id']),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_PRODUCTTAX_NOT_FOUND");
                return "";
            }
            $producttaxBS = new ProducttaxBS();
            $producttaxBS->json = $this->json;
            parent::completeByJsonFkVf($producttaxBS);
            if (!empty($cod)) {
                $producttaxBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $producttaxBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_PRODUCTTAX_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $producttaxBS = !empty($bs) ? $bs : new ProducttaxBS();
            $producttaxBS->json = $this->json;
            parent::completeByJsonFkVf($producttaxBS);
            parent::evalConditions($producttaxBS, $conditions);
            parent::evalOrders($producttaxBS, $orders);
            $producttaxs = $producttaxBS->table($conditions, $orders, $paginate);
            parent::evalPagination($producttaxBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($producttaxs);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($producttaxIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $producttax = DelegateUtility::getEntityToSave(new ProducttaxBS(), $producttaxIn, $this->obj);

            if (!empty($producttax)) {

                $producttaxBS = new ProducttaxBS();
                $id_producttax = $producttaxBS->save($producttax);
                parent::saveInGroup($producttaxBS, $id_producttax);

                parent::commitTransaction();
                if (!empty($id_producttax)) {
                    $this->ok(TranslatorUtility::__translate("INFO_PRODUCTTAX_SAVE", $this->localefile));
                    return $id_producttax;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_PRODUCTTAX_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_PRODUCTTAX_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PRODUCTTAX_SAVE");
            return 0;
        }
    }

    function edit($id, $producttaxIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $producttax = DelegateUtility::getEntityToEdit(new ProducttaxBS(), $producttaxIn, $this->obj, $id);

            if (!empty($producttax)) {
                $producttaxBS = new ProducttaxBS();
                $id_producttax = $producttaxBS->save($producttax);
                parent::saveInGroup($producttaxBS, $id_producttax);
                parent::delInGroup($producttaxBS, $id_producttax);

                parent::commitTransaction();
                if (!empty($id_producttax)) {
                    $this->ok(TranslatorUtility::__translate("INFO_PRODUCTTAX_EDIT", $this->localefile));
                    return $id_producttax;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_PRODUCTTAX_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_PRODUCTTAX_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PRODUCTTAX_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $producttaxBS = new ProducttaxBS();
                $producttaxBS->delete($id);
                parent::delInGroup($producttaxBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_PRODUCTTAX_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_PRODUCTTAX_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PRODUCTTAX_DELETE");
            return false;
        }
    }
}
