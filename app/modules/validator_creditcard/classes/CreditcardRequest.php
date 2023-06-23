<?php
class CreditcardRequest {
    public $number = "";
    public $type = "";
    public $expireMM = "";
    public $expireYY = "";
    public $cvc = "";

    function notFilled() {
        $arr = array();
        if (empty($this->number)) {
            array_push($arr, "number");
        }
        if (empty($this->type)) {
            array_push($arr, "type");
        }
        if (empty($this->expireMM)) {
            array_push($arr, "expireMM");
        }
        if (empty($this->expireYY)) {
            array_push($arr, "expireYY");
        }
        if (empty($this->cvc)) {
            array_push($arr, "cvc");
        }
        return $arr;
    }
}