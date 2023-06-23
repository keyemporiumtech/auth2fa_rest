<?php

class CreditcardModel {
    public $input;
    public $type = "";
    public $pattern = "";
    public $format = "";
    public $length = array();
    public $cvcLength = array();
    public $luhn = false;
    public $number = "";
    public $validNumber = false;
    public $validDate = false;
    public $validCvc = false;
}