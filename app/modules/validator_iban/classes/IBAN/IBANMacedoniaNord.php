<?php
App::uses("Codes", "Config/system");
App::uses("IBANGeneric", "modules/validator_iban/classes");
App::uses("TranslatorUtility", "modules/cakeutils/utility");

class IBANMacedoniaNord extends IBANGeneric {

    static function converter($iban) {
        $LEN = 19;
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
        $obj->bankcod = substr($iban, 4, 3);
        $obj->cc = substr($iban, 7, 10);
        $obj->controlcod = substr($iban, 17, 2);
        $obj->pattern = "cod_iso3166-0-2|controlnumbers-2-2|bankcod-4-3|cc-7-10|controlcod-17-2";
        $obj->length = $LEN;
        $obj->bban = $obj->bankcod . $obj->cc . $obj->controlcod;
        parent::setLabelsPatternByModel($obj);
        return $obj;
    }
}