<?php
App::uses("Codes", "Config/system");
App::uses("IBANGeneric", "modules/validator_iban/classes");
App::uses("TranslatorUtility", "modules/cakeutils/utility");

class IBANLettonia extends IBANGeneric {

    static function converter($iban) {
        $LEN = 21;
        $iban = str_replace(" ", "", $iban);
        if (strlen($iban) < $LEN) {
            throw new Exception(TranslatorUtility::__translate_args('ERRON_VALIDATE_LESS_THEN', array($LEN), 'iban'), Codes::get('ERROR_VALIDATOR_IBAN_LESS_THEN'));
        }
        if (strlen($iban) > $LEN) {
            throw new Exception(TranslatorUtility::__translate_args('ERRON_VALIDATE_GREATER_THEN', array($LEN), 'iban'), Codes::get('ERROR_VALIDATOR_IBAN_GREATER_THEN'));
        }
        $obj = new IBANModel($iban);
        $obj->cod_iso3166 = substr($iban, 0, 2);
        $obj->controlnumbers = substr($iban, 2, 2);
        $obj->bic = substr($iban, 4, 4);
        $obj->cc = substr($iban, 8, 13);
        $obj->pattern = "cod_iso3166-0-2|controlnumbers-2-2|bic-4-4|cc-8-13";
        $obj->length = $LEN;
        $obj->bban = $obj->bic . $obj->cc;
        parent::setLabelsPatternByModel($obj);
        return $obj;
    }
}