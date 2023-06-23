<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("BasketBS", "modules/shop_payment/business");
App::uses("FileUtility", "modules/coreutils/utility");

class BasketUI extends AppGenericUI {

    function __construct() {
        parent::__construct("BasketUI");
        $this->localefile = "basket";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("website", null, ""),
            new ObjPropertyEntity("title", null, ""),
            new ObjPropertyEntity("flgclosed", null, 0),
            new ObjPropertyEntity("flgreserve", null, 0),
            new ObjPropertyEntity("user", null, 0),
            new ObjPropertyEntity("activity", null, 0),
            new ObjPropertyEntity("email", null, ""),
            new ObjPropertyEntity("phone", null, ""),
            new ObjPropertyEntity("emailto", null, ""),
            new ObjPropertyEntity("phoneto", null, ""),
            new ObjPropertyEntity("strto", null, ""),
            new ObjPropertyEntity("note", null, ""),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_BASKET_NOT_FOUND");
                return "";
            }
            $basketBS = new BasketBS();
            $basketBS->json = $this->json;
            parent::completeByJsonFkVf($basketBS);
            if (!empty($cod)) {
                $basketBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $basketBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_BASKET_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $basketBS = !empty($bs) ? $bs : new BasketBS();
            $basketBS->json = $this->json;
            parent::completeByJsonFkVf($basketBS);
            parent::evalConditions($basketBS, $conditions);
            parent::evalOrders($basketBS, $orders);
            $baskets = $basketBS->table($conditions, $orders, $paginate);
            parent::evalPagination($basketBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($baskets);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($basketIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $basket = DelegateUtility::getEntityToSave(new BasketBS(), $basketIn, $this->obj);

            if (!empty($basket)) {

                $basketBS = new BasketBS();
                $id_basket = $basketBS->save($basket);
                parent::saveInGroup($basketBS, $id_basket);

                parent::commitTransaction();
                if (!empty($id_basket)) {
                    $this->ok(TranslatorUtility::__translate("INFO_BASKET_SAVE", $this->localefile));
                    return $id_basket;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_BASKET_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_BASKET_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_BASKET_SAVE");
            return 0;
        }
    }

    function edit($id, $basketIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $basket = DelegateUtility::getEntityToEdit(new BasketBS(), $basketIn, $this->obj, $id);

            if (!empty($basket)) {
                $basketBS = new BasketBS();
                $id_basket = $basketBS->save($basket);
                parent::saveInGroup($basketBS, $id_basket);
                parent::delInGroup($basketBS, $id_basket);

                parent::commitTransaction();
                if (!empty($id_basket)) {
                    $this->ok(TranslatorUtility::__translate("INFO_BASKET_EDIT", $this->localefile));
                    return $id_basket;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_BASKET_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_BASKET_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_BASKET_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $basketBS = new BasketBS();
                $basketBS->delete($id);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_BASKET_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_BASKET_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_BASKET_DELETE");
            return false;
        }
    }
}
