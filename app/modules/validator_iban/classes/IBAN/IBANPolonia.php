<?php
App::uses("Codes", "Config/system");
App::uses("IBANGeneric", "modules/validator_iban/classes");
App::uses("TranslatorUtility", "modules/cakeutils/utility");

class IBANPolonia extends IBANGeneric {

    static function converter($iban) {
        $LEN = 28;
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
        $obj->bankorg = substr($iban, 7, 4);
        $obj->labelorg_cod = EnumIBANOrg::AGENCY;
        $obj->controlkey = substr($iban, 11, 1);
        $obj->cc = substr($iban, 12, 16);
        $obj->pattern = "cod_iso3166-0-2|controlnumbers-2-2|bankcod-4-3|bankorg-7-4|controlkey-11-1|cc-12-16";
        $obj->length = $LEN;
        $obj->bban = $obj->bankcod . $obj->bankorg . $obj->controlkey . $obj->cc;
        parent::setLabelsPatternByModel($obj);
        return $obj;
    }
}