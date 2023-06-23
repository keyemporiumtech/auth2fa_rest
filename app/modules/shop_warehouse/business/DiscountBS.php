<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Discount", "Model");

class DiscountBS extends AppGenericBS {

    function __construct() {
        parent::__construct('Discount');
    }

    function cleanExpiredForeign($tableRelation, $fieldEntity, $idEntity) {
        $search = "SELECT id FROM discounts WHERE dtaend IS NOT NULL AND dtaend < '" . date('Y-m-d H:i:s') . "' AND id IN";
        $search .= " (SELECT discount FROM {$tableRelation} WHERE {$fieldEntity}={$idEntity})";
        $results = $this->query($search);
        if (!ArrayUtility::isEmpty($results)) {
            $ids = "";
            for ($i = 0; $i < count($results); $i++) {
                $obj = $results[$i];
                if ($i != 0) {
                    $ids .= "," . $obj['Discount']['id'];
                } else {
                    $ids .= $obj['Discount']['id'];
                }
            }

            $del1 = "DELETE FROM {$tableRelation} WHERE {$fieldEntity}={$idEntity} AND discount IN ($ids)";
            $del2 = "DELETE FROM discounts WHERE id IN ($ids)";
            $this->execute($del1);
            $this->execute($del2);
        }
    }

    function deleteForeign($tableRelation, $fieldEntity, $idEntity, $id) {
        $del1 = "DELETE FROM {$tableRelation} WHERE {$fieldEntity}={$idEntity} AND discount =$id";
        $del2 = "DELETE FROM discounts WHERE id IN ($id)";
        $this->execute($del1);
        $this->execute($del2);
    }
}
