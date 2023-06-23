<?php
App::uses("ArrayUtility", "modules/coreutils/utility");
App::uses("StringUtility", "modules/coreutils/utility");
App::uses('EnumQueryLike', 'modules/cakeutils/config');
// inner
App::uses("BalanceBS", "modules/shop_payment/business");
App::uses("PaymentBS", "modules/shop_payment/business");
App::uses("PriceBS", "modules/shop_warehouse/business");
App::uses("BalancepaymentBS", "modules/shop_payment/business");
App::uses("GrouprelationBS", "modules/cakeutils/business");

class PaymentUtility {
    static function getPaymentCod($payment, $id_balance) {
        $cod = "";
        if ($payment['Payment']['flgin'] == 0) {
            $cod = "OUT-PAY";
        } else if ($payment['Payment']['flgin'] == 1) {
            $cod = "IN-PAY";
        } else {
            throw new Exception("flgin is not valid to build payment cod");
        }

        $balanceBS = new BalanceBS();
        $balance = $balanceBS->unique($id_balance);
        if ($balance) {
            $cod .= strtoupper($balance['Balance']['cod']);
        } else {
            throw new Exception("Balance is not valid to build payment cod");
        }

        if (!empty($payment['Payment']['dtapayment'])) {
            $cod .= str_replace("-", "", $payment['Payment']['dtapayment']);
        } else {
            throw new Exception("dtapayment is not valid to build payment cod");
        }
        return $cod;
    }

    static function getPaymentCodByDuplicate($payment, $id_balance) {
        $cod = PaymentUtility::getPaymentCod($payment, $id_balance);

        $paymentBS = new PaymentBS();
        $paymentBS->addLike("cod", $cod, EnumQueryLike::RIGHT);
        $payments = $paymentBS->all();

        if (!ArrayUtility::isEmpty($payments)) {
            $max = 1;
            if (count($payments) == 1) {
                $paymentDuplicate = $payments[0];
                $codSearch = str_replace("-PAY", "", $paymentDuplicate['Payment']['cod']);
                if (!StringUtility::contains($codSearch, "-")) {
                    $paymentBS = new PaymentBS();
                    $paymentBS->updateField($paymentDuplicate['Payment']['id'], "cod", $paymentDuplicate['Payment']['cod'] . "-1");
                    $max = 1;
                } else {
                    $arrValues = explode("-", $codSearch);
                    if (count($arrValues) == 2 && $arrValues[1] >= $max) {
                        $max = $arrValues[1];
                    }
                }

            } else {
                $codSearch = null;
                foreach ($payments as $paymentDuplicate) {
                    $codSearch = str_replace("-PAY", "", $paymentDuplicate['Payment']['cod']);
                    if (StringUtility::contains($codSearch, "-")) {
                        $arrValues = explode("-", $codSearch);
                        if (count($arrValues) == 2 && $arrValues[1] >= $max) {
                            $max = $arrValues[1];
                        }
                    }
                }
            }
            $cod .= "-" . ($max + 1);
        }
        return $cod;
    }

    static function setPaymentCod(&$payment, $id_balance) {
        $payment['Payment']['cod'] = PaymentUtility::getPaymentCodByDuplicate($payment, $id_balance);
    }

    // ----- CRUD
    static function deletePayment($id = null, $cod = null) {
        if (empty($id) && empty($cod)) {
            throw new Exception("Input parameters NULL");
        }
        $payment = null;
        if (!empty($id)) {
            $paymentBS = new PaymentBS();
            $payment = $paymentBS->unique($id);
            if (!empty($payment)) {
                $cod = $payment['Payment']['cod'];
            }
        } else {
            $paymentBS = new PaymentBS();
            $paymentBS->addCondition("cod", $cod);
            $payment = $paymentBS->unique();
        }

        // --- delete price
        $priceBS = new PriceBS();
        $priceBS->delete($payment['Payment']['price']);

        // --- delete balancepayment
        $balancepaymentBS = new BalancepaymentBS();
        $sql = "DELETE FROM balancepayments WHERE payment=" . $payment['Payment']['id'];
        $balancepaymentBS->execute($sql);

        // --- delete group relation
        $grouprelationBS = new GrouprelationBS();
        $sql = "DELETE FROM grouprelations WHERE tableid=" . $payment['Payment']['id'] . " AND tablename='payments'";
        $grouprelationBS->execute($sql);

        // --- delete payment
        $paymentBS = new PaymentBS();
        $paymentBS->delete($payment['Payment']['id']);
    }
}
