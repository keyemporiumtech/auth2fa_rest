<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("PaymentBS", "modules/shop_payment/business");
App::uses("FileUtility", "modules/coreutils/utility");
App::uses("PaymentUtility", "modules/shop_payment/utility");

class PaymentUI extends AppGenericUI {

    function __construct() {
        parent::__construct("PaymentUI");
        $this->localefile = "payment";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("price", null, 0),
            new ObjPropertyEntity("flgin", null, 1),
            new ObjPropertyEntity("paymentmethod", null, 0),
            new ObjPropertyEntity("dtapayment", null, ""),
            new ObjPropertyEntity("note", null, ""),
            new ObjPropertyEntity("causal", null, ""),
            new ObjPropertyEntity("bank_sender", null, ""),
            new ObjPropertyEntity("bank_receiver", null, ""),
            new ObjPropertyEntity("flgconfirm", null, 0),
            new ObjPropertyEntity("tppayment", null, 0),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_PAYMENT_NOT_FOUND");
                return "";
            }
            $paymentBS = new PaymentBS();
            $paymentBS->json = $this->json;
            parent::completeByJsonFkVf($paymentBS);
            if (!empty($cod)) {
                $paymentBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $paymentBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_PAYMENT_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $paymentBS = !empty($bs) ? $bs : new PaymentBS();
            $paymentBS->json = $this->json;
            parent::completeByJsonFkVf($paymentBS);
            parent::evalConditions($paymentBS, $conditions);
            parent::evalOrders($paymentBS, $orders);
            $payments = $paymentBS->table($conditions, $orders, $paginate);
            parent::evalPagination($paymentBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($payments);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($paymentIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $payment = DelegateUtility::getEntityToSave(new PaymentBS(), $paymentIn, $this->obj);

            if (!empty($payment)) {

                $paymentBS = new PaymentBS();
                $id_payment = $paymentBS->save($payment);
                parent::saveInGroup($paymentBS, $id_payment);

                parent::commitTransaction();
                if (!empty($id_payment)) {
                    $this->ok(TranslatorUtility::__translate("INFO_PAYMENT_SAVE", $this->localefile));
                    return $id_payment;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_PAYMENT_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_PAYMENT_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PAYMENT_SAVE");
            return 0;
        }
    }

    function edit($id, $paymentIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $payment = DelegateUtility::getEntityToEdit(new PaymentBS(), $paymentIn, $this->obj, $id);

            if (!empty($payment)) {
                $paymentBS = new PaymentBS();
                $id_payment = $paymentBS->save($payment);
                parent::saveInGroup($paymentBS, $id_payment);
                parent::delInGroup($paymentBS, $id_payment);

                parent::commitTransaction();
                if (!empty($id_payment)) {
                    $this->ok(TranslatorUtility::__translate("INFO_PAYMENT_EDIT", $this->localefile));
                    return $id_payment;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_PAYMENT_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_PAYMENT_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PAYMENT_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                // $paymentBS = new PaymentBS();
                // $paymentBS->delete($id);
                PaymentUtility::deletePayment($id);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_PAYMENT_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_PAYMENT_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PAYMENT_DELETE");
            return false;
        }
    }

    // ---------------- TYPOLOGICAL
    function tppayment($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "tppayment";
        try {
            $typologicalUI = new TypologicalUI("Tppayment", "shop_payment");
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

    // ------------------ PRICE
    function addPrice($priceIn, $id) {
        $this->LOG_FUNCTION = "addPrice";
        try {
            if (empty($priceIn) || empty($id)) {
                DelegateUtility::paramsNull($this, "ERROR_PAYMENT_EDIT");
                return false;
            }
            parent::startTransaction();

            DelegatePriceUtility::addPrice($priceIn, $id, "Payment");

            parent::commitTransaction();
            $this->ok(TranslatorUtility::__translate("ERROR_PAYMENT_EDIT", $this->localefile));
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PAYMENT_EDIT");
            return false;
        }
    }

    function editPrice($priceIn, $id) {
        $this->LOG_FUNCTION = "editPrice";
        try {
            if (empty($priceIn) || empty($id)) {
                DelegateUtility::paramsNull($this, "ERROR_PAYMENT_EDIT");
                return false;
            }
            parent::startTransaction();

            DelegatePriceUtility::editPrice($priceIn, $id, "Payment");

            parent::commitTransaction();
            $this->ok(TranslatorUtility::__translate("ERROR_PAYMENT_EDIT", $this->localefile));
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PAYMENT_EDIT");
            return false;
        }
    }

}
