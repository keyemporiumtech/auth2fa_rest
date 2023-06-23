<?php
App::uses("Codes", "Config/system");
App::uses("IBANGeneric", "modules/validator_iban/classes");
App::uses("TranslatorUtility", "modules/cakeutils/utility");

class IBANBosniaErzegovina extends IBANGeneric {

    static function converter($iban) {
        $LEN = 20;
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
        $obj->bankorg = substr($iban, 7, 3);
        $obj->labelorg_cod = EnumIBANOrg::FRONT_OFFICE;
        $obj->cc = substr($iban, 10, 8);
        $obj->controlkey = substr($iban, 18, 2);
        $obj->pattern = "cod_iso3166-0-2|controlnumbers-2-2|bankcod-4-3|bankorg-7-3|cc-10-8|controlkey-18-2";
        $obj->length = $LEN;
        $obj->bban = $obj->bankcod . $obj->bankorg . $obj->cc . $obj->controlkey;
        parent::setLabelsPatternByModel($obj);
        return $obj;
    }
}