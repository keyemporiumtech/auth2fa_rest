<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("BasketproductBS", "modules/shop_payment/business");
App::uses("FileUtility", "modules/coreutils/utility");

class BasketproductUI extends AppGenericUI {

    function __construct() {
        parent::__construct("BasketproductUI");
        $this->localefile = "basketproduct";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("title", null, ""),
            new ObjPropertyEntity("product", null, 0),
            new ObjPropertyEntity("basket", null, 0),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_BASKETPRODUCT_NOT_FOUND");
                return "";
            }
            $basketproductBS = new BasketproductBS();
            $basketproductBS->json = $this->json;
            parent::completeByJsonFkVf($basketproductBS);
            if (!empty($cod)) {
                $basketproductBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $basketproductBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_BASKETPRODUCT_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $basketproductBS = !empty($bs) ? $bs : new BasketproductBS();
            $basketproductBS->json = $this->json;
            parent::completeByJsonFkVf($basketproductBS);
            parent::evalConditions($basketproductBS, $conditions);
            parent::evalOrders($basketproductBS, $orders);
            $basketproducts = $basketproductBS->table($conditions, $orders, $paginate);
            parent::evalPagination($basketproductBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($basketproducts);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($basketproductIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $basketproduct = DelegateUtility::getEntityToSave(new BasketproductBS(), $basketproductIn, $this->obj);

            if (!empty($basketproduct)) {

                $basketproductBS = new BasketproductBS();
                $id_basketproduct = $basketproductBS->save($basketproduct);
                parent::saveInGroup($basketproductBS, $id_basketproduct);

                parent::commitTransaction();
                if (!empty($id_basketproduct)) {
                    $this->ok(TranslatorUtility::__translate("INFO_BASKETPRODUCT_SAVE", $this->localefile));
                    return $id_basketproduct;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_BASKETPRODUCT_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_BASKETPRODUCT_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_BASKETPRODUCT_SAVE");
            return 0;
        }
    }

    function edit($id, $basketproductIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $basketproduct = DelegateUtility::getEntityToEdit(new BasketproductBS(), $basketproductIn, $this->obj, $id);

            if (!empty($basketproduct)) {
                $basketproductBS = new BasketproductBS();
                $id_basketproduct = $basketproductBS->save($basketproduct);
                parent::saveInGroup($basketproductBS, $id_basketproduct);
                parent::delInGroup($basketproductBS, $id_basketproduct);

                parent::commitTransaction();
                if (!empty($id_basketproduct)) {
                    $this->ok(TranslatorUtility::__translate("INFO_BASKETPRODUCT_EDIT", $this->localefile));
                    return $id_basketproduct;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_BASKETPRODUCT_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_BASKETPRODUCT_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_BASKETPRODUCT_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $basketproductBS = new BasketproductBS();
                $basketproductBS->delete($id);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_BASKETPRODUCT_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_BASKETPRODUCT_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_BASKETPRODUCT_DELETE");
            return false;
        }
    }
}
