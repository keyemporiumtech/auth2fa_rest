<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("PaymentmethodBS", "modules/shop_payment/business");
App::uses("FileUtility", "modules/coreutils/utility");

class PaymentmethodUI extends AppGenericUI {

    function __construct() {
        parent::__construct("PaymentmethodUI");
        $this->localefile = "paymentmethod";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("name", null, ""),
            new ObjPropertyEntity("intest", null, ""),
            new ObjPropertyEntity("description", null, ""),
            new ObjPropertyEntity("tppaymentmethod", null, 0),
            new ObjPropertyEntity("tpwebpayment", null, 0),
            new ObjPropertyEntity("user", null, 0),
            new ObjPropertyEntity("activity", null, 0),
            new ObjPropertyEntity("email", null, ""),
            new ObjPropertyEntity("account_id", null, ""),
            new ObjPropertyEntity("iban", null, ""),
            new ObjPropertyEntity("bban", null, ""),
            new ObjPropertyEntity("swift_bic", null, ""),
            new ObjPropertyEntity("swift", null, ""),
            new ObjPropertyEntity("bic", null, ""),
            new ObjPropertyEntity("abi", null, ""),
            new ObjPropertyEntity("cab", null, ""),
            new ObjPropertyEntity("cin", null, ""),
            new ObjPropertyEntity("bank", null, ""),
            new ObjPropertyEntity("bank_address", null, ""),
            new ObjPropertyEntity("cc", null, ""),
            new ObjPropertyEntity("card", null, ""),
            new ObjPropertyEntity("card_number", null, ""),
            new ObjPropertyEntity("card_deadline_m", null, ""),
            new ObjPropertyEntity("card_deadline_y", null, ""),
            new ObjPropertyEntity("cvv", null, ""),
            new ObjPropertyEntity("cvv2", null, ""),
            new ObjPropertyEntity("cvc", null, ""),
            new ObjPropertyEntity("typein", null, 0),
            new ObjPropertyEntity("typeout", null, 0),
            new ObjPropertyEntity("flgonline", null, 0),
            new ObjPropertyEntity("flgdefault", null, 0),
            new ObjPropertyEntity("signin", null, ""),
            new ObjPropertyEntity("signout", null, ""),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_PAYMENTMETHOD_NOT_FOUND");
                return "";
            }
            $paymentmethodBS = new PaymentmethodBS();
            $paymentmethodBS->json = $this->json;
            parent::completeByJsonFkVf($paymentmethodBS);
            if (!empty($cod)) {
                $paymentmethodBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $paymentmethodBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_PAYMENTMETHOD_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $paymentmethodBS = !empty($bs) ? $bs : new PaymentmethodBS();
            $paymentmethodBS->json = $this->json;
            parent::completeByJsonFkVf($paymentmethodBS);
            parent::evalConditions($paymentmethodBS, $conditions);
            parent::evalOrders($paymentmethodBS, $orders);
            $paymentmethods = $paymentmethodBS->table($conditions, $orders, $paginate);
            parent::evalPagination($paymentmethodBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($paymentmethods);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($paymentmethodIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $paymentmethod = DelegateUtility::getEntityToSave(new PaymentmethodBS(), $paymentmethodIn, $this->obj);

            if (!empty($paymentmethod)) {

                $paymentmethodBS = new PaymentmethodBS();
                $id_paymentmethod = $paymentmethodBS->save($paymentmethod);
                parent::saveInGroup($paymentmethodBS, $id_paymentmethod);

                parent::commitTransaction();
                if (!empty($id_paymentmethod)) {
                    $this->ok(TranslatorUtility::__translate("INFO_PAYMENTMETHOD_SAVE", $this->localefile));
                    return $id_paymentmethod;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_PAYMENTMETHOD_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_PAYMENTMETHOD_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PAYMENTMETHOD_SAVE");
            return 0;
        }
    }

    function edit($id, $paymentmethodIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $paymentmethod = DelegateUtility::getEntityToEdit(new PaymentmethodBS(), $paymentmethodIn, $this->obj, $id);

            if (!empty($paymentmethod)) {
                $paymentmethodBS = new PaymentmethodBS();
                $id_paymentmethod = $paymentmethodBS->save($paymentmethod);
                parent::saveInGroup($paymentmethodBS, $id_paymentmethod);
                parent::delInGroup($paymentmethodBS, $id_paymentmethod);

                parent::commitTransaction();
                if (!empty($id_paymentmethod)) {
                    $this->ok(TranslatorUtility::__translate("INFO_PAYMENTMETHOD_EDIT", $this->localefile));
                    return $id_paymentmethod;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_PAYMENTMETHOD_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_PAYMENTMETHOD_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PAYMENTMETHOD_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $paymentmethodBS = new PaymentmethodBS();
                $paymentmethodBS->delete($id);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_PAYMENTMETHOD_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_PAYMENTMETHOD_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PAYMENTMETHOD_DELETE");
            return false;
        }
    }

    // ---------------- TYPOLOGICAL
    function tppaymentmethod($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "tppaymentmethod";
        try {
            $typologicalUI = new TypologicalUI("Tppaymentmethod", "shop_payment");
            $typologicalUI->json = $this->json;
            parent::assignToDelegate($typologicalUI);
            $result = $typologicalUI->table($conditions, $orders, $paginate, $bs);
            parent::mappingDelegate($typologicalUI);
            return $result;
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function tpwebpayment($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "tpwebpayment";
        try {
            $typologicalUI = new TypologicalUI("Tpwebpayment", "shop_payment");
            $typologicalUI->json = $this->json;
            parent::assignToDelegate($typologicalUI);
            $result = $typologicalUI->table($conditions, $orders, $paginate, $bs);
            parent::mappingDelegate($typologicalUI);
            return $result;
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }
}
