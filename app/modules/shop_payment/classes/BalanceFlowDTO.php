<?php

class BalanceFlowDTO {
    public $priceIn = 0.00;
    public $priceOut = 0.00;
    public $ivaIn = 0.00;
    public $ivaOut = 0.00;
    public $discountIn = 0.00;
    public $discountOut = 0.00;
    public $taxIn = 0.00;
    public $taxOut = 0.00;
    public $totalIn = 0.00;
    public $totalOut = 0.00;
    public $totalsumIn = 0.00;
    public $totalsumOut = 0.00;
    public $currencyCod;
    public $currencyTitle;
    public $currencySymbol;
    public $currencyIcon;
    // info
    public $deposit = 0.00;
    public $payed = 0.00;
    // payments
    public $payments = array();
    public $pages = 1;
    public $count = 0;
    // grouped
    public $groupcod;
    public $month;
}