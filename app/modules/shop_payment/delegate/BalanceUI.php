<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("BalanceBS", "modules/shop_payment/business");
App::uses("FileUtility", "modules/coreutils/utility");
App::uses("CurrencyUtility", "modules/util_currency/utility");
App::uses("BalanceUtility", "modules/shop_payment/utility");
App::uses("PaymentUtility", "modules/shop_payment/utility");
App::uses("BalanceFlowFilter", "modules/shop_payment/classes");
App::uses("PaymentBS", "modules/shop_payment/business");
App::uses("PaymentUI", "modules/shop_payment/delegate");
App::uses("BalancepaymentBS", "modules/shop_payment/business");
App::uses("PriceBS", "modules/shop_warehouse/business");
App::uses("PriceUI", "modules/shop_warehouse/delegate");

class BalanceUI extends AppGenericUI {

    function __construct() {
        parent::__construct("BalanceUI");
        $this->localefile = "balance";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("title", null, ""),
            new ObjPropertyEntity("description", null, ""),
            new ObjPropertyEntity("user", null, 0),
            new ObjPropertyEntity("activity", null, 0),
            new ObjPropertyEntity("initdeposit", null, 0.00),
            new ObjPropertyEntity("currencyid", null, CurrencyUtility::getCurrencySystem()['Currency']['id']),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_BALANCE_NOT_FOUND");
                return "";
            }
            $balanceBS = new BalanceBS();
            $balanceBS->json = $this->json;
            parent::completeByJsonFkVf($balanceBS);
            if (!empty($cod)) {
                $balanceBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $balanceBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_BALANCE_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $balanceBS = !empty($bs) ? $bs : new BalanceBS();
            $balanceBS->json = $this->json;
            parent::completeByJsonFkVf($balanceBS);
            parent::evalConditions($balanceBS, $conditions);
            parent::evalOrders($balanceBS, $orders);
            $balances = $balanceBS->table($conditions, $orders, $paginate);
            parent::evalPagination($balanceBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($balances);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($balanceIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $balance = DelegateUtility::getEntityToSave(new BalanceBS(), $balanceIn, $this->obj);

            if (!empty($balance)) {

                $balanceBS = new BalanceBS();
                $id_balance = $balanceBS->save($balance);
                parent::saveInGroup($balanceBS, $id_balance);

                parent::commitTransaction();
                if (!empty($id_balance)) {
                    $this->ok(TranslatorUtility::__translate("INFO_BALANCE_SAVE", $this->localefile));
                    return $id_balance;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_BALANCE_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_BALANCE_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_BALANCE_SAVE");
            return 0;
        }
    }

    function edit($id, $balanceIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $balance = DelegateUtility::getEntityToEdit(new BalanceBS(), $balanceIn, $this->obj, $id);

            if (!empty($balance)) {
                $balanceBS = new BalanceBS();
                $id_balance = $balanceBS->save($balance);
                parent::saveInGroup($balanceBS, $id_balance);
                parent::delInGroup($balanceBS, $id_balance);

                parent::commitTransaction();
                if (!empty($id_balance)) {
                    $this->ok(TranslatorUtility::__translate("INFO_BALANCE_EDIT", $this->localefile));
                    return $id_balance;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_BALANCE_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_BALANCE_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_BALANCE_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $balanceBS = new BalanceBS();
                $balanceBS->delete($id);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_BALANCE_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_BALANCE_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_BALANCE_DELETE");
            return false;
        }
    }

    // PAYMENTS
    function addPayment($paymentIn = null, $id_payment = null, $priceIn = null, $id = null) {
        $this->LOG_FUNCTION = "addPayment";
        try {
            if ((empty($paymentIn) && empty($id_payment)) || empty($id)) {
                DelegateUtility::paramsNull($this, "ERROR_BALANCE_EDIT");
                return false;
            }
            parent::startTransaction();
            $paymentUI = new PaymentUI();
            $paymentBS = new PaymentBS();
            if (!empty($paymentIn)) {
                $paymentObj = DelegateUtility::getObj(true, $paymentIn);
                $payment = !empty($paymentObj->id) ?
                DelegateUtility::getEntityToEdit($paymentBS, $paymentIn, $paymentUI->obj, $paymentObj->id) :
                DelegateUtility::getEntityToSave($paymentBS, $paymentIn, $paymentUI->obj);
                PaymentUtility::setPaymentCod($payment, $id);
                $id_payment = $paymentBS->save($payment);
                parent::saveInGroup($paymentBS, $id_payment);
                parent::delInGroup($paymentBS, $id_payment);
            } else {
                $payment = $paymentBS->unique($id_payment);
                $cod = PaymentUtility::getPaymentCodByDuplicate($payment, $id);
                if ($cod != $payment['Payment']['cod']) {
                    $paymentBS->updateField($payment['Payment']['id'], "cod", $cod);
                }
            }

            if (!empty($priceIn)) {
                $priceUI = new PriceUI();
                $priceBS = new PriceBS();
                $priceObj = DelegateUtility::getObj(true, $priceIn);
                $price = !empty($priceObj->id) ?
                DelegateUtility::getEntityToSave($priceBS, $priceIn, $priceUI->obj, $priceObj->id) :
                DelegateUtility::getEntityToSave($priceBS, $priceIn, $priceUI->obj);
                if (!empty($payment['Payment']['price'])) {
                    $price['Price']['id'] = $payment['Payment']['price'];
                }
                $price['Price']['cod'] = "PR" . $payment['Payment']['cod'];
                $id_price = $priceBS->save($price);
                $paymentBS = new PaymentBS();
                $paymentBS->updateField($id_payment, 'price', $id_price);
            }

            $balancepaymentBS = new BalancepaymentBS();
            $balancepaymentBS->acceptNull = true;
            $balancepaymentBS->addCondition("balance", $id);
            $balancepaymentBS->addCondition("payment", $id_payment);
            $balancepayment = $balancepaymentBS->unique();

            if (empty($balancepayment)) {
                $balancepaymentBS = new BalancepaymentBS();
                $balancepayment = $balancepaymentBS->instance();
                $balancepayment['Balancepayment']['balance'] = $id;
                $balancepayment['Balancepayment']['payment'] = $id_payment;
                $balancepayment['Balancepayment']['cod'] = "BLC" . $payment['Payment']['cod'];
                $id_balancepayment = $balancepaymentBS->save($balancepayment);
            }

            parent::commitTransaction();
            $this->ok(TranslatorUtility::__translate("INFO_BALANCE_EDIT", $this->localefile));
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_BALANCE_EDIT");
            return false;
        }
    }

    function payments($id_balance = null, $cod_balance = null, $id_balances = null, $cod_balances = null, $id_user = null, $username = null, $id_activity = null, $piva = null, $filters = null) {
        $this->LOG_FUNCTION = "payments";
        try {
            if (
                empty($id_balance) && empty($cod_balance) &&
                empty($id_balances) && empty($cod_balances) &&
                empty($id_user) && empty($username) &&
                empty($id_activity) && empty($piva)
            ) {
                DelegateUtility::paramsNull($this, "ERROR_BALANCE_NOT_FOUND");
                return false;
            }

            $objFilter = new BalanceFlowFilter();
            if (!empty($filters)) {
                $objFilter = SystemUtility::castObject(DelegateUtility::getObj($this->json, $filters), 'BalanceFlowFilter'); // BalanceFlowFilter
            }

            $objFlow = null;
            if (!empty($id_balance) || !empty($cod_balance)) {
                $objFlow = BalanceUtility::getBalanceFlowByBalance($id_balance, $cod_balance, $objFilter);
            } elseif (!empty($id_balances) || !empty($cod_balances)) {
                $objFlow = BalanceUtility::getBalanceFlowByBalances($id_balances, $cod_balances, $objFilter);
            } elseif (!empty($id_user) || !empty($username)) {
                $objFlow = BalanceUtility::getBalanceFlowByUser($id_user, $username, $objFilter);
            } elseif (!empty($id_activity) || !empty($piva)) {
                $objFlow = BalanceUtility::getBalanceFlowByActivity($id_activity, $piva, $objFilter);
            }

            $this->ok();
            return $this->json ? json_encode($objFlow, true) : $objFlow;
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_BALANCE_NOT_FOUND");
            return false;
        }
    }
}
