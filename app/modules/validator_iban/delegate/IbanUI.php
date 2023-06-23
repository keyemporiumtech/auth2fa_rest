<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("Codes", "Config/system");
App::uses("ObjValidator", "modules/cakeutils/classes");
App::uses("TranslatorUtility", "modules/cakeutils/utility");
App::uses("IBANUtility", "modules/validator_iban/utility");
App::uses("NationBS", "modules/localesystem/business");
App::uses("NationUI", "modules/localesystem/delegate");

class IbanUI extends AppGenericUI {

    function __construct() {
        parent::__construct("IbanUI");
        $this->localefile = "iban";
        $this->obj = null;
    }

    function validate($iban = null, $nationcod = null) {
        $this->LOG_FUNCTION = "validate";
        $validation = new ObjValidator();
        try {
            if (empty($iban) || empty($nationcod)) {
                DelegateUtility::paramsNull($this, "ERRON_VALIDATE");
                $validation = new ObjValidator(false, TranslatorUtility::__translate('ERRON_VALIDATE_NOT_VALID', $this->localefile));
                return $this->json ? json_encode($validation) : $validation;
            }
            $iban = IBANUtility::getIBANModel($iban, $nationcod);
            $this->ok();
            $validation = new ObjValidator(true, null, $iban);
            return $this->json ? json_encode($validation) : $validation;
        } catch (Exception $e) {
            $validation = $this->manageExceptionIban($e);
            return $this->json ? json_encode($validation) : $validation;
        }
    }

    private function manageExceptionIban(Exception $e) {
        switch ($e->getCode()) {
        case Codes::get('ERROR_VALIDATOR_IBAN'):
            DelegateUtility::eccezione($e, $this, 'ERRON_VALIDATE_NOT_FOUND');
            return new ObjValidator(false, TranslatorUtility::__translate('ERRON_VALIDATE_NOT_FOUND', $this->localefile));
        case Codes::get('ERROR_VALIDATOR_IBAN_LESS_THEN'):
        case Codes::get('ERROR_VALIDATOR_IBAN_GREATER_THEN'):
            $this->warning($e->getMessage(), $e, null, $e->getCode());
            return new ObjValidator(false, $e->getMessage());
        default:
            DelegateUtility::eccezione($e, $this, "ERRON_VALIDATE");
            return new ObjValidator(false, TranslatorUtility::__translate('ERRON_VALIDATE_NOT_VALID', $this->localefile));
        }
    }

    function tpiban($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "tpiban";
        try {
            $bs = new NationBS();
            $bs->addCondition("flgiban", 1);
            $bs->addCondition("cod_iso3166", IBANUtility::getAvailableNations());
            $ui = new NationUI;
            $ui->json = $this->json;
            parent::assignToDelegate($ui);
            $nations = $ui->table($conditions, $orders, $paginate, $bs);
            parent::mappingDelegate($ui);
            return $nations;
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

}
