<?php
class ObjValidator {
    public $valid; // boolean
    public $message; // string
    public $payload; // oggetto

    function __construct($v = false, $m = null, $p = null) {
        $this->valid = $v;
        $this->message = $m;
        $this->payload = $p;
    }
}