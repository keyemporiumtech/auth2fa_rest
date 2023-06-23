<?php
App::uses("ArrayUtility", "modules/coreutils/utility");
App::uses("CurrencyUtility", "modules/util_currency/utility");
// inner
App::uses("PriceBS", "modules/shop_warehouse/business");
App::uses("BalanceBS", "modules/shop_payment/business");
App::uses("BalancepaymentBS", "modules/shop_payment/business");
App::uses("PaymentBS", "modules/shop_payment/business");
App::uses("BalanceFlowDTO", "modules/shop_payment/classes");
App::uses("BalanceFlowFilter", "modules/shop_payment/classes");
App::uses("UserBS", "modules/authentication/business");
App::uses("ActivityBS", "modules/authentication/business");

class BalanceUtility {
    // ------------------------ FILTERS
    static function filterPaymentsPrice($id_balances, BalanceFlowFilter $filters, $flgin = true) {
        $sql = "SELECT SUM(price) as price, SUM(iva) as iva, SUM(discount) as discount, SUM(tax) as tax, SUM(total) as total, COUNT(*) as num FROM prices as Price";

        $sqlPayments = "SELECT DISTINCT p.price FROM payments as p INNER JOIN balancepayments as bp ON (p.id = bp.payment)";
        $sqlPayments .= " WHERE bp.balance IN(" . ArrayUtility::getStringByList($id_balances, false, ",", "'") . ")";
        $sqlPayments .= " AND p.flgin=" . ($flgin ? "1" : "0");
        if (!empty($filters->dtaFrom)) {
            $sqlPayments .= " AND p.dtapayment >='{$filters->dtaFrom}'";
        }
        if (!empty($filters->dtaTo)) {
            $sqlPayments .= " AND p.dtapayment <='{$filters->dtaTo}'";
        }
        if (!ArrayUtility::isEmpty($filters->groups)) {
            $sqlPayments .= " AND p.id IN (SELECT DISTINCT tableid FROM grouprelations WHERE tablename='payments' AND groupcod IN (" . ArrayUtility::getStringByList($filters->groups, false, ",", "'") . "))";
        }
        if (!empty($filters->causal)) {
            $sqlPayments .= " AND p.causal LIKE '%{$filters->causal}%'";
        }

        $sql .= " WHERE id IN ({$sqlPayments})";

        $priceBS = new PriceBS();
        return $priceBS->genericQuery($sql);
    }

    static function filterGroupPrice($id_balances, BalanceFlowFilter $filters, $flgin = true) {
        $sql = "SELECT pay.groupcod as groupcod, SUM(Price.price) as price, SUM(Price.iva) as iva, SUM(Price.discount) as discount, SUM(Price.tax) as tax, SUM(Price.total) as total, COUNT(*) as num  FROM prices as Price";

        $sqlPayments = "SELECT DISTINCT p.price, gr.groupcod as groupcod FROM payments as p INNER JOIN balancepayments as bp ON (p.id = bp.payment),";
        $sqlPayments .= "(SELECT DISTINCT tableid, cod, groupcod FROM grouprelations WHERE tablename='payments') as gr";
        $sqlPayments .= " WHERE bp.balance IN(" . ArrayUtility::getStringByList($id_balances, false, ",", "'") . ")";
        $sqlPayments .= " AND p.flgin=" . ($flgin ? "1" : "0");
        $sqlPayments .= " AND p.id=gr.tableid";
        if (!empty($filters->dtaFrom)) {
            $sqlPayments .= " AND p.dtapayment >='{$filters->dtaFrom}'";
        }
        if (!empty($filters->dtaTo)) {
            $sqlPayments .= " AND p.dtapayment <='{$filters->dtaTo}'";
        }
        if (!ArrayUtility::isEmpty($filters->groups)) {
            $sqlPayments .= " AND gr.groupcod IN (" . ArrayUtility::getStringByList($filters->groups, false, ",", "'") . ")";
        }
        if (!empty($filters->causal)) {
            $sqlPayments .= " AND p.causal LIKE '%{$filters->causal}%'";
        }

        $sql .= " ,({$sqlPayments}) as pay";
        $sql .= " WHERE Price.id=pay.price";
        $sql .= " GROUP BY pay.groupcod";

        $priceBS = new PriceBS();
        return $priceBS->genericQuery($sql);
    }

    // --------------------- BALANCE FLOW

    static function getBalancesFlow($id_balances, BalanceFlowFilter $filters) {
        if ($filters->year) {
            return BalanceUtility::getBalancesFlowMonths($id_balances, $filters);
        }
        if ($filters->flgGroupcod) {
            return BalanceUtility::getBalancesFlowGroups($id_balances, $filters);
        }
        return BalanceUtility::getBalancesFlowPayments($id_balances, $filters);
    }

    static function getBalancesFlowMonths($id_balances, BalanceFlowFilter $filters) {
        $balances = array();
        for ($i = 1; $i <= 12; $i++) {
            $newFilter = new BalanceFlowFilter();
            $newFilter->dtaFrom = $filters->dtaFrom;
            $newFilter->dtaTo = $filters->dtaTo;
            $newFilter->groups = $filters->groups;
            $newFilter->causal = $filters->causal;
            $newFilter->limit = $filters->limit;
            $newFilter->page = $filters->page;
            $newFilter->flgPayments = $filters->flgPayments;
            $newFilter->flgGroupcod = $filters->flgGroupcod;
            $newFilter->year = $filters->year;

            $month = $i <= 9 ? "0{$i}" : $i;
            $dtaFrom = "{$filters->year}-{$month}-01";
            $dtaTo = "{$filters->year}-{$month}-31";

            if (!empty($filters->dtaFrom)) {
                $yFrom = substr($filters->dtaFrom, 0, 4);
                $mFrom = substr($filters->dtaFrom, 5, 2);
                $dFrom = substr($filters->dtaFrom, 8, 2);
                if ($filters->year == $yFrom && $month == $mFrom) {
                    $dtaFrom = "{$filters->year}-{$month}-{$dFrom}";
                }
            }

            $newFilter->dtaFrom = $dtaFrom;

            if (!empty($filters->dtaTo)) {
                $yTo = substr($filters->dtaTo, 0, 4);
                $mTo = substr($filters->dtaTo, 5, 2);
                $dTo = substr($filters->dtaTo, 8, 2);
                if ($filters->year == $yTo && $month == $mTo) {
                    $dtaTo = "{$filters->year}-{$month}-{$dTo}";
                }
            }

            $newFilter->dtaTo = $dtaTo;

            $balanceFlowDTO = BalanceUtility::getBalancesFlowPayments($id_balances, $newFilter);
            $balanceFlowDTO->month = $month;
            array_push($balances, $balanceFlowDTO);
        }
        return $balances;
    }
    static function getBalancesFlowGroups($id_balances, BalanceFlowFilter $filters) {
        $countsIn = BalanceUtility::filterGroupPrice($id_balances, $filters, true);
        $countsOut = BalanceUtility::filterGroupPrice($id_balances, $filters, false);
        return BalanceUtility::getBalanceFlowDTOGroups($id_balances, $countsIn, $countsOut, $filters);
    }

    static function getBalancesFlowPayments($id_balances, BalanceFlowFilter $filters) {
        $countsIn = BalanceUtility::filterPaymentsPrice($id_balances, $filters, true);
        $countsOut = BalanceUtility::filterPaymentsPrice($id_balances, $filters, false);
        $ids = array();
        if ($filters->flgPayments) {
            $filtersBS = new PaymentBS();
            $filtersID = $filtersBS->filterPayments($id_balances, $filters->dtaFrom, $filters->dtaTo, $filters->groups, $filters->causal, $filters->limit, $filters->page);
            foreach ($filtersID as $filterId) {
                array_push($ids, $filterId['Payment']['id']);
            }
        }
        return BalanceUtility::getBalanceFlowDTOPayments($ids, $countsIn, $countsOut, $filters->limit, $filters->avoidContent);
    }

    static function getBalanceFlowDTOGroups($id_balances = array(), $countsIn = array(), $countsOut = array(), $filters = null) {

        $balances = array();

        $balanceFlowDTO = null;

        // IN
        $countsObj = null;
        foreach ($countsIn as $in) {
            $countsObj = $in[0];
            $objIndex = ArrayUtility::getObjectIndexByFieldAndValue($balances, $in['pay']['groupcod'], "groupcod");
            $balanceFlowDTO = empty($objIndex) ? new BalanceFlowDTO() : $balances[$objIndex];

            $balanceFlowDTO->groupcod = $in['pay']['groupcod'];

            $price = $countsObj['price'];
            $iva = $countsObj['iva'];
            $discount = $countsObj['discount'];
            $tax = $countsObj['tax'];
            $total = $countsObj['total'];
            $totalsum = ($price + $iva - $discount + $tax);
            $balanceFlowDTO->priceIn = round($price, 2);
            $balanceFlowDTO->ivaIn = round($iva, 2);
            $balanceFlowDTO->discountIn = round($discount, 2);
            $balanceFlowDTO->taxIn = round($tax, 2);
            $balanceFlowDTO->totalIn = round($total, 2);
            $balanceFlowDTO->totalsumIn = round($totalsum, 2);
            if (empty($balanceFlowDTO->currencyCod)) {
                $currency = CurrencyUtility::getCurrencySystem();
                $balanceFlowDTO->currencyCod = $currency['Currency']['cod'];
                $balanceFlowDTO->currencyTitle = $currency['Currency']['title'];
                $balanceFlowDTO->currencySymbol = $currency['Currency']['symbol'];
                if (!$filters->avoidContent) {
                    $balanceFlowDTO->currencyIcon = !empty($currency['Currency']['iconimage']) ? $currency['Currency']['iconimage'] : null;
                }
            }

            if ($filters->flgPayments) {
                $filtersBS = new PaymentBS();
                $filters->groups = array($in['pay']['groupcod']);
                $filtersID = $filtersBS->filterPayments($id_balances, $filters->dtaFrom, $filters->dtaTo, $filters->groups, $filters->causal, $filters->limit, $filters->page);
                $ids = array();
                foreach ($filtersID as $filterId) {
                    array_push($ids, $filterId['Payment']['id']);
                }
                BalanceUtility::fillPayments($balanceFlowDTO, $ids, $countsObj['num']);
            }

            if (empty($objIndex)) {
                array_push($balances, $balanceFlowDTO);
            } else {
                $balances[$objIndex] = $balanceFlowDTO;
            }
        }

        // IN
        $countsObj = null;
        foreach ($countsOut as $out) {
            $countsObj = $out[0];
            $objIndex = ArrayUtility::getObjectIndexByFieldAndValue($balances, $out['pay']['groupcod'], "groupcod");
            $balanceFlowDTO = empty($objIndex) ? new BalanceFlowDTO() : $balances[$objIndex];

            $balanceFlowDTO->groupcod = $out['pay']['groupcod'];

            $price = $countsObj['price'];
            $iva = $countsObj['iva'];
            $discount = $countsObj['discount'];
            $tax = $countsObj['tax'];
            $total = $countsObj['total'];
            $totalsum = ($price + $iva - $discount + $tax);
            $balanceFlowDTO->priceOut = round($price, 2);
            $balanceFlowDTO->ivaOut = round($iva, 2);
            $balanceFlowDTO->discountOut = round($discount, 2);
            $balanceFlowDTO->taxOut = round($tax, 2);
            $balanceFlowDTO->totalOut = round($total, 2);
            $balanceFlowDTO->totalsumOut = round($totalsum, 2);
            if (empty($balanceFlowDTO->currencyCod)) {
                $currency = CurrencyUtility::getCurrencySystem();
                $balanceFlowDTO->currencyCod = $currency['Currency']['cod'];
                $balanceFlowDTO->currencyTitle = $currency['Currency']['title'];
                $balanceFlowDTO->currencySymbol = $currency['Currency']['symbol'];
                if (!$filters->avoidContent) {
                    $balanceFlowDTO->currencyIcon = !empty($currency['Currency']['iconimage']) ? $currency['Currency']['iconimage'] : null;
                }
            }

            if ($filters->flgPayments) {
                $filtersBS = new PaymentBS();
                $filters->groups = array($out['pay']['groupcod']);
                $filtersID = $filtersBS->filterPayments($id_balances, $filters->dtaFrom, $filters->dtaTo, $filters->groups, $filters->causal, $filters->limit, $filters->page);
                $ids = array();
                foreach ($filtersID as $filterId) {
                    array_push($ids, $filterId['Payment']['id']);
                }
                BalanceUtility::fillPayments($balanceFlowDTO, $ids, $countsObj['num']);
            }

            if (empty($objIndex)) {
                array_push($balances, $balanceFlowDTO);
            } else {
                $balances[$objIndex] = $balanceFlowDTO;
            }
        }

        return $balances;
    }

    static function getBalanceFlowDTOPayments($ids = array(), $countsIn = array(), $countsOut = array(), $limit = null, $avoidContent = false) {
        $countsInObj = $countsIn[0][0];
        $countsOutObj = $countsOut[0][0];
        $priceIn = $countsInObj['price'];
        $priceOut = $countsOutObj['price'];
        $ivaIn = $countsInObj['iva'];
        $ivaOut = $countsOutObj['iva'];
        $discountIn = $countsInObj['discount'];
        $discountOut = $countsOutObj['discount'];
        $taxIn = $countsInObj['tax'];
        $taxOut = $countsOutObj['tax'];
        $totalIn = $countsInObj['total'];
        $totalOut = $countsOutObj['total'];
        $totalsumIn = ($priceIn + $ivaIn - $discountIn + $taxIn);
        $totalsumOut = ($priceOut + $ivaOut - $discountOut + $taxOut);

        $balanceFlowDTO = new BalanceFlowDTO();

        BalanceUtility::fillPayments($balanceFlowDTO, $ids, $countsInObj['num'] + $countsOutObj['num']);

        $balanceFlowDTO->priceIn = round($priceIn, 2);
        $balanceFlowDTO->priceOut = round($priceOut, 2);
        $balanceFlowDTO->ivaIn = round($ivaIn, 2);
        $balanceFlowDTO->ivaOut = round($ivaOut, 2);
        $balanceFlowDTO->discountIn = round($discountIn, 2);
        $balanceFlowDTO->discountOut = round($discountOut, 2);
        $balanceFlowDTO->taxIn = round($taxIn, 2);
        $balanceFlowDTO->taxOut = round($taxOut, 2);
        $balanceFlowDTO->totalIn = round($totalIn, 2);
        $balanceFlowDTO->totalOut = round($totalOut, 2);
        $balanceFlowDTO->totalsumIn = round($totalsumIn, 2);
        $balanceFlowDTO->totalsumOut = round($totalsumOut, 2);
        $currency = CurrencyUtility::getCurrencySystem();
        $balanceFlowDTO->currencyCod = $currency['Currency']['cod'];
        $balanceFlowDTO->currencyTitle = $currency['Currency']['title'];
        $balanceFlowDTO->currencySymbol = $currency['Currency']['symbol'];
        if (!$avoidContent) {
            $balanceFlowDTO->currencyIcon = !empty($currency['Currency']['iconimage']) ? $currency['Currency']['iconimage'] : null;
        }

        return $balanceFlowDTO;
    }

    static function fillPayments(&$balanceFlowDTO, $ids = array(), $counts = null) {
        if (!ArrayUtility::isEmpty($ids)) {
            $paymentBS = new PaymentBS();
            $paymentBS->addBelongsTo("price_fk");
            $paymentBS->addCondition("Payment.id", $ids);
            $paymentBS->addPropertyDao("flgAllGroups", true);
            $payments = $paymentBS->all();

            $countAll = $counts;
            $balanceFlowDTO->count = $countAll;
            if (!empty($limit)) {
                $balanceFlowDTO->pages = ceil($countAll / $limit);
            }
            foreach ($payments as $payment) {
                array_push($balanceFlowDTO->payments, $payment['Payment']);
            }
        }
    }

    // --------------------- BALANCES QUERY
    static function getBalanceFlowByBalances($ids = array(), $cods = array(), BalanceFlowFilter $filters) {
        $balanceBS = new BalanceBS();
        if (!ArrayUtility::isEmpty($cods)) {
            $balanceBS->addCondition("cod", $cods);
        }
        if (!ArrayUtility::isEmpty($ids)) {
            $balanceBS->addCondition("id", $ids);
        }
        $balances = $balanceBS->all();

        if (!ArrayUtility::isEmpty($balances)) {
            $id_balances = array();
            foreach ($balances as $balance) {
                array_push($id_balances, $balance['Balance']['id']);
            }
            return BalanceUtility::getBalancesFlow($id_balances, $filters);
        } else {
            throw new Exception("Balances not found");
        }
    }
    static function getBalanceFlowByBalance($id = null, $cod = null, BalanceFlowFilter $filters) {
        $balanceBS = new BalanceBS();
        if (!empty($cod)) {
            $balanceBS->addCondition("cod", $cod);
        }
        $balance = $balanceBS->unique($id);

        if (!empty($balance)) {
            return BalanceUtility::getBalancesFlow(array($balance['Balance']['id']), $filters);
        } else {
            throw new Exception("Balance not found");
        }
    }

    static function getBalanceFlowByUser($id = null, $username = null, BalanceFlowFilter $filters) {
        $userBS = new UserBS();
        if (!empty($username)) {
            $userBS->addCondition("username", $username);
        }
        $user = $userBS->unique($id);

        if (!empty($user)) {
            $balanceBS = new BalanceBS();
            $balanceBS->addCondition("user", $user['User']['id']);
            $balances = $balanceBS->all();
            $ids = array();
            foreach ($balances as $balance) {
                array_push($ids, $balance['Balance']['id']);
            }
            return BalanceUtility::getBalancesFlow($ids, $filters);
        } else {
            throw new Exception("User not found");
        }
    }

    static function getBalanceFlowByActivity($id = null, $piva = null, BalanceFlowFilter $filters) {
        $activityBS = new ActivityBS();
        if (!empty($piva)) {
            $activityBS->addCondition("piva", $piva);
        }
        $activity = $activityBS->unique($id);

        if (!empty($activity)) {
            $balanceBS = new BalanceBS();
            $balanceBS->addCondition("activity", $activity['Activity']['id']);
            $balances = $balanceBS->all();
            $ids = array();
            foreach ($balances as $balance) {
                array_push($ids, $balance['Balance']['id']);
            }
            return BalanceUtility::getBalancesFlow($ids, $filters);
        } else {
            throw new Exception("Activity not found");
        }
    }

    // UTILS
    /**
     * @param BalanceFlowDTO $balanceTo
     * @param BalanceFlowDTO $balanceFrom
     */
    static function mergeBalanceFlow(&$balanceTo, BalanceFlowDTO $balanceFrom) {
        if (!empty($balanceFrom)) {
            if ($balanceFrom->currencyCod != $balanceTo->currencyCod) {
                $balanceFrom->priceIn = CurrencyUtility::convert($balanceFrom->currencyCod, $balanceTo->currencyCod, $balanceFrom->priceIn);
                $balanceFrom->priceOut = CurrencyUtility::convert($balanceFrom->currencyCod, $balanceTo->currencyCod, $balanceFrom->priceOut);
                $balanceFrom->ivaIn = CurrencyUtility::convert($balanceFrom->currencyCod, $balanceTo->currencyCod, $balanceFrom->ivaIn);
                $balanceFrom->ivaOut = CurrencyUtility::convert($balanceFrom->currencyCod, $balanceTo->currencyCod, $balanceFrom->ivaOut);
                $balanceFrom->discountIn = CurrencyUtility::convert($balanceFrom->currencyCod, $balanceTo->currencyCod, $balanceFrom->discountIn);
                $balanceFrom->discountOut = CurrencyUtility::convert($balanceFrom->currencyCod, $balanceTo->currencyCod, $balanceFrom->discountOut);
                $balanceFrom->taxIn = CurrencyUtility::convert($balanceFrom->currencyCod, $balanceTo->currencyCod, $balanceFrom->taxIn);
                $balanceFrom->taxOut = CurrencyUtility::convert($balanceFrom->currencyCod, $balanceTo->currencyCod, $balanceFrom->taxOut);
                $balanceFrom->totalIn = CurrencyUtility::convert($balanceFrom->currencyCod, $balanceTo->currencyCod, $balanceFrom->totalIn);
                $balanceFrom->totalOut = CurrencyUtility::convert($balanceFrom->currencyCod, $balanceTo->currencyCod, $balanceFrom->totalOut);
                $balanceFrom->totalsumIn = CurrencyUtility::convert($balanceFrom->currencyCod, $balanceTo->currencyCod, $balanceFrom->totalsumIn);
                $balanceFrom->totalsumOut = CurrencyUtility::convert($balanceFrom->currencyCod, $balanceTo->currencyCod, $balanceFrom->totalsumOut);
                $balanceFrom->deposit = CurrencyUtility::convert($balanceFrom->currencyCod, $balanceTo->currencyCod, $balanceFrom->deposit);
                $balanceFrom->payed = CurrencyUtility::convert($balanceFrom->currencyCod, $balanceTo->currencyCod, $balanceFrom->payed);
            }

            $priceIn = $balanceTo->priceIn + $balanceFrom->priceIn;
            $priceOut = $balanceTo->priceOut + $balanceFrom->priceOut;
            $ivaIn = $balanceTo->ivaIn + $balanceFrom->ivaIn;
            $ivaOut = $balanceTo->ivaOut + $balanceFrom->ivaOut;
            $discountIn = $balanceTo->discountIn + $balanceFrom->discountIn;
            $discountOut = $balanceTo->discountOut + $balanceFrom->discountOut;
            $taxIn = $balanceTo->taxIn + $balanceFrom->taxIn;
            $taxOut = $balanceTo->taxOut + $balanceFrom->taxOut;
            $totalIn = $balanceTo->totalIn + $balanceFrom->totalIn;
            $totalOut = $balanceTo->totalOut + $balanceFrom->totalOut;
            $totalsumIn = $balanceTo->totalsumIn + $balanceFrom->totalsumIn;
            $totalsumOut = $balanceTo->totalsumOut + $balanceFrom->totalsumOut;
            $deposit = $balanceTo->deposit + $balanceFrom->deposit;
            $payed = $balanceTo->payed + $balanceFrom->payed;

            $balanceTo->priceIn = round($priceIn, 2);
            $balanceTo->priceOut = round($priceOut, 2);
            $balanceTo->ivaIn = round($ivaIn, 2);
            $balanceTo->ivaOut = round($ivaOut, 2);
            $balanceTo->discountIn = round($discountIn, 2);
            $balanceTo->discountOut = round($discountOut, 2);
            $balanceTo->taxIn = round($taxIn, 2);
            $balanceTo->taxOut = round($taxOut, 2);
            $balanceTo->totalIn = round($totalIn, 2);
            $balanceTo->totalOut = round($totalOut, 2);
            $balanceTo->totalsumIn = round($totalsumIn, 2);
            $balanceTo->totalsumOut = round($totalsumOut, 2);
            $balanceTo->deposit = round($deposit, 2);
            $balanceTo->payed = round($payed, 2);
        }

    }
}
