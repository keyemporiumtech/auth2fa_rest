<?php

class BalanceFlowFilter {
    public $dtaFrom;
    public $dtaTo;
    public $groups = array();
    public $causal;
    public $limit;
    public $page;
    // flags
    public $flgPayments = false; // include la lista di pagamenti
    public $flgGroupcod = false; // raggruppa per gruppi
    public $year; // conteggi raggruppati per mesi dell'anno
    public $avoidContent = false; // non carica le icone della valuta
}