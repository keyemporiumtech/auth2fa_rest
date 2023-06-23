<?php
App::uses("ArrayUtility", "modules/coreutils/utility");
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("DbUtility", "modules/cakeutils/utility");
App::uses("Payment", "Model");

class PaymentBS extends AppGenericBS {

    function __construct() {
        parent::__construct('Payment');
    }

    function filterPayments($id_balances = array(), $dtaFrom = null, $dtaTo = null, $groups = array(), $causal = null, $limit = null, $page = null) {
        $sql = "SELECT DISTINCT Payment.id FROM payments as Payment WHERE 1";
        $sql .= " AND Payment.id IN (SELECT DISTINCT payment FROM balancepayments WHERE balance IN(" . ArrayUtility::getStringByList($id_balances, false, ",", "'") . "))";
        if (!empty($dtaFrom)) {
            $sql .= " AND Payment.dtapayment >='{$dtaFrom}'";
        }
        if (!empty($dtaTo)) {
            $sql .= " AND Payment.dtapayment <='{$dtaTo}'";
        }
        if (!ArrayUtility::isEmpty($groups)) {
            $sql .= " AND Payment.id IN (SELECT DISTINCT tableid FROM grouprelations WHERE tablename='" . $this->dao->useTable . "' AND groupcod IN (" . ArrayUtility::getStringByList($groups, false, ",", "'") . "))";
        }
        if (!empty($causal)) {
            $sql .= " AND Payment.causal LIKE '%{$causal}%'";
        }
        $sql .= " ORDER BY Payment.dtapayment ASC";
        if (!empty($limit)) {
            $page = empty($page) ? 1 : $page;
            $sql .= " LIMIT " . DBUtility::getOffsetByLimitAndPage($limit, $page);
        }
        return $this->query($sql, false);
    }
}
