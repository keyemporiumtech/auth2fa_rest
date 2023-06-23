<?php
App::uses("Codes", "Config/system");
App::uses("IBANGeneric", "modules/validator_iban/classes");
App::uses("TranslatorUtility", "modules/cakeutils/utility");

class IBANNorvegia extends IBANGeneric {

    static function converter($iban) {
        $LEN = 15;
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
        $obj->bankcod = substr($iban, 4, 4);
        $obj->cc = substr($iban, 8, 6);
        $obj->controlkey = substr($iban, 14, 1);
        $obj->pattern = "cod_iso3166-0-2|controlnumbers-2-2|bankcod-4-4|cc-8-6|controlkey-14-1";
        $obj->length = $LEN;
        $obj->bban = $obj->bankcod . $obj->cc . $obj->controlkey;
        parent::setLabelsPatternByModel($obj);
        return $obj;
    }
}