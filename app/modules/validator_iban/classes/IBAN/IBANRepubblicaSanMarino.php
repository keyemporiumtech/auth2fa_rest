<?php
App::uses("Codes", "Config/system");
App::uses("IBANGeneric", "modules/validator_iban/classes");
App::uses("TranslatorUtility", "modules/cakeutils/utility");

class IBANRepubblicaSanMarino extends IBANGeneric {

    static function converter($iban) {
        $LEN = 27;
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
        $obj->cin = substr($iban, 4, 1);
        $obj->abi = substr($iban, 5, 5);
        $obj->cab = substr($iban, 10, 5);
        $obj->cc = substr($iban, 15, 12);
        $obj->pattern = "cod_iso3166-0-2|controlnumbers-2-2|cin-4-1|abi-5-5|cab-10-5|cc-15-12";
        $obj->length = $LEN;
        $obj->bban = $obj->cin . $obj->abi . $obj->cab . $obj->cc;
        parent::setLabelsPatternByModel($obj);
        return $obj;
    }
}