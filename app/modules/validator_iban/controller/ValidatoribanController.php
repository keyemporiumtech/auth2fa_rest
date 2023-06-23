<?php
App::uses('AppController', 'Controller');
App::uses("IBANUtility", "modules/validator_iban/utility");

class ValidatoribanController extends AppController {

    public function home() {
    }

    public function format($cod = null) {
        parent::evalParam($cod, "cod");
        $nations = empty($cod) ? IBANUtility::getAvailableNations() : array($cod);
        $values = array();
        foreach ($nations as $cod) {
            $values[$cod] = IBANUtility::getExampleIBAN($cod);
        }
        $this->set("ibans", $values);
    }

    public function iban($iban = null, $cod = null) {
        parent::evalParam($iban, "iban");
        parent::evalParam($cod, "cod");
        $nations = empty($cod) ? IBANUtility::getAvailableNations() : array($cod);
        $values = array();
        $invalids = array();
        foreach ($nations as $cod) {
            try {
                $values[$cod] = IBANUtility::getIBANModel($iban, $cod);
            } catch (Exception $e) {
                array_push($invalids, "iban $iban non valido per $cod");
            }

        }
        $this->set("ibans", $values);
        $this->set("invalids", $invalids);
    }

}