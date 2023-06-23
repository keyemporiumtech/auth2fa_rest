<?php

class MessageStatus {
    // INDICA SE è UN ERRORE (WARNING, ERROR, FATAL) O UNA INFO [EnumMessageStatus]
    private $__statuscod;
    // MESSAGGIO DA MOSTRARE (i codici sono gestiti applicativamente)
    private $__applicationMessage;
    // MESSAGGIO DI ECCEZIONE (i codici sono quelli di sistema 200...500)
    private $__exceptionMessage;
    // MESSAGGIO INTERNO (i codici sono quelli di sistema 200...500)
    private $__internalMessage;
    // CODICE DELLA RESPONSE (importante quando la response torna 200 anche in caso di errore)
    private $__responsecod;

    /**
     * Costruisce un oggetto per i messaggi e lo stato di una response
     * @param int $status INDICA SE è UN ERRORE (WARNING, ERROR, FATAL) O UNA INFO [@see EnumMessageStatus]
     * @param ObjCodMessage $message MESSAGGIO DA MOSTRARE (i codici sono gestiti applicativamente)
     * @param ObjCodMessage $exception MESSAGGIO DI ECCEZIONE (i codici sono quelli di sistema 200...500)
     * @param ObjCodMessage $internal MESSAGGIO INTERNO (i codici sono quelli di sistema 200...500)
     * @param int $responsecod CODICE DELLA RESPONSE (importante quando la response torna 200 anche in caso di errore)
     */
    public function __construct($status, ObjCodMessage $message, ObjCodMessage $exception = null, ObjCodMessage $internal = null, $responsecod = null) {
        $this->statuscod = $status;
        $this->applicationMessage = $message;
        $this->exceptionMessage = $exception;
        $this->internalMessage = $internal;
        $this->responsecod = $responsecod;
    }

    public function getStatusCod() {
        return $this->statuscod;
    }

    public function getResponseCod() {
        return $this->responsecod;
    }

    public function getMessageType() {
        return !empty($this->applicationMessage) ? $this->applicationMessage->type : null;
    }

    public function getCod() {
        return !empty($this->applicationMessage) ? $this->applicationMessage->cod : null;
    }

    public function getMessage() {
        return !empty($this->applicationMessage) ? $this->applicationMessage->message : null;
    }

    public function getExceptionCod() {
        return !empty($this->exceptionMessage) ? $this->exceptionMessage->cod : null;
    }

    public function getExceptionMessage() {
        return !empty($this->exceptionMessage) ? $this->exceptionMessage->message : null;
    }

    public function getInternalCod() {
        return !empty($this->internalMessage) ? $this->internalMessage->cod : null;
    }

    public function getInternalMessage() {
        return !empty($this->internalMessage) ? $this->internalMessage->message : null;
    }
}