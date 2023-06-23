<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("BasketticketBS", "modules/shop_payment/business");
App::uses("FileUtility", "modules/coreutils/utility");

class BasketticketUI extends AppGenericUI {

    function __construct() {
        parent::__construct("BasketticketUI");
        $this->localefile = "basketticket";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("title", null, ""),
            new ObjPropertyEntity("ticket", null, 0),
            new ObjPropertyEntity("basket", null, 0),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_BASKETTICKET_NOT_FOUND");
                return "";
            }
            $basketticketBS = new BasketticketBS();
            $basketticketBS->json = $this->json;
            parent::completeByJsonFkVf($basketticketBS);
            if (!empty($cod)) {
                $basketticketBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $basketticketBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_BASKETTICKET_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $basketticketBS = !empty($bs) ? $bs : new BasketticketBS();
            $basketticketBS->json = $this->json;
            parent::completeByJsonFkVf($basketticketBS);
            parent::evalConditions($basketticketBS, $conditions);
            parent::evalOrders($basketticketBS, $orders);
            $baskettickets = $basketticketBS->table($conditions, $orders, $paginate);
            parent::evalPagination($basketticketBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($baskettickets);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($basketticketIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $basketticket = DelegateUtility::getEntityToSave(new BasketticketBS(), $basketticketIn, $this->obj);

            if (!empty($basketticket)) {

                $basketticketBS = new BasketticketBS();
                $id_basketticket = $basketticketBS->save($basketticket);
                parent::saveInGroup($basketticketBS, $id_basketticket);

                parent::commitTransaction();
                if (!empty($id_basketticket)) {
                    $this->ok(TranslatorUtility::__translate("INFO_BASKETTICKET_SAVE", $this->localefile));
                    return $id_basketticket;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_BASKETTICKET_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_BASKETTICKET_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_BASKETTICKET_SAVE");
            return 0;
        }
    }

    function edit($id, $basketticketIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $basketticket = DelegateUtility::getEntityToEdit(new BasketticketBS(), $basketticketIn, $this->obj, $id);

            if (!empty($basketticket)) {
                $basketticketBS = new BasketticketBS();
                $id_basketticket = $basketticketBS->save($basketticket);
                parent::saveInGroup($basketticketBS, $id_basketticket);
                parent::delInGroup($basketticketBS, $id_basketticket);

                parent::commitTransaction();
                if (!empty($id_basketticket)) {
                    $this->ok(TranslatorUtility::__translate("INFO_BASKETTICKET_EDIT", $this->localefile));
                    return $id_basketticket;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_BASKETTICKET_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_BASKETTICKET_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_BASKETTICKET_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $basketticketBS = new BasketticketBS();
                $basketticketBS->delete($id);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_BASKETTICKET_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_BASKETTICKET_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_BASKETTICKET_DELETE");
            return false;
        }
    }
}
