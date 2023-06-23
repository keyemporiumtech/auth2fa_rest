<?php
App::uses('AppController', 'Controller');
App::uses("CreditcardUtility", "modules/validator_creditcard/utility");
App::uses("CreditcardRequest", "modules/validator_creditcard/classes");

class ValidatorcreditcardController extends AppController {

    public function home() {
    }

    public function type($cod = null) {
        parent::evalParam($cod, "cod");
        $cards = CreditcardUtility::getAvailableTypes();
        $values = array();
        if (!empty($cod)) {
            $values[$cod] = $cards[$cod];
        } else {
            $values = $cards;
        }
        $this->set("types", $values);
    }

    public function creditcard($number = null, $expireMM = null, $expireYY = null, $cvc = null) {
        parent::evalParam($number, "number");
        parent::evalParam($expireMM, "expireMM");
        parent::evalParam($expireYY, "expireYY");
        parent::evalParam($cvc, "cvc");

        $cc = null;
        $message = null;
        try {
            $request = new CreditcardRequest();
            $request->number = $number;
            $request->expireMM = $expireMM;
            $request->expireYY = $expireYY;
            $request->cvc = $cvc;
            $cc = CreditcardUtility::getCreditcardModel($request);
        } catch (Exception $e) {
            $message = $e->getMessage();
        }

        $this->set("cc", $cc);
        $this->set("message", $message);
    }

}