<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");

class ErrorUI extends AppGenericUI {

    function __construct() {
        parent::__construct("ErrorUI");
    }

    function apierror($cod = null, $message = null, $exception = null, $exceptioncod = null, $internal = null, $internalcod = null, $responsecod = null) {
        $this->LOG_FUNCTION = "error";
        $this->error(mb_convert_encoding("" . __d("errors", "ERROR_MESSAGE", array(
            $message,
            $internalcod,
        )), 'UTF-8'), $exception, $internal, $cod, $exceptioncod, $internalcod, $responsecod);
        $error = array(
            "statuscod" => $this->status->getStatusCod(),
            "responsecod" => $this->status->getResponseCod(),
            "cod" => $this->status->getCod(),
            "message" => $this->status->getMessage(),
            "exceptioncod" => $this->status->getExceptionCod(),
            "exception" => $this->status->getExceptionMessage(),
            "internalcod" => $this->status->getInternalCod(),
            "internal" => $this->status->getInternalMessage(),
        )
        ;
        return json_encode($error);
    }
}