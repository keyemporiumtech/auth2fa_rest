<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("Codes", "Config/system");
App::uses("ObjValidator", "modules/cakeutils/classes");
App::uses("TranslatorUtility", "modules/cakeutils/utility");
App::uses("CreditcardRequest", "modules/validator_creditcard/classes");
App::uses("CreditcardUtility", "modules/validator_creditcard/utility");

class CreditcardUI extends AppGenericUI {

    function __construct() {
        parent::__construct("CreditcardUI");
        $this->localefile = "creditcard";
        $this->obj = null;
    }

    function validate($num_cc = null, $mm = null, $yy = null, $cvc = null, $type = null) {
        $this->LOG_FUNCTION = "validate";
        $validation = new ObjValidator();

        try {
            if (empty($num_cc) || empty($mm) || empty($yy) || empty($cvc)) {
                DelegateUtility::paramsNull($this, "ERRON_VALIDATE");
                $validation = new ObjValidator(false, TranslatorUtility::__translate('ERRON_VALIDATE_NOT_VALID', $this->localefile));
                return $this->json ? json_encode($validation) : $validation;
            }

            $request = new CreditcardRequest();
            $request->number = $num_cc;
            $request->expireMM = $mm;
            $request->expireYY = $yy;
            $request->cvc = $cvc;
            $request->type = $type;
            $cc = CreditcardUtility::getCreditcardModel($request);
            $this->ok();
            $validation = new ObjValidator(true, null, $cc);
            return $this->json ? json_encode($validation) : $validation;
        } catch (Exception $e) {
            $validation = $this->manageExceptionIban($e);
            return $this->json ? json_encode($validation) : $validation;
        }
    }

    function validateRequest($requestIn = null) {
        $this->LOG_FUNCTION = "validateRequest";
        $validation = new ObjValidator();

        try {
            if (empty($requestIn)) {
                DelegateUtility::paramsNull($this, "ERRON_VALIDATE");
                $validation = new ObjValidator(false, TranslatorUtility::__translate('ERRON_VALIDATE_NOT_VALID', $this->localefile));
                return $this->json ? json_encode($validation) : $validation;
            }

            $request = DelegateUtility::getObj($this->json, $requestIn);
            $cc = CreditcardUtility::getCreditcardModel($request);
            $this->ok();
            $validation = new ObjValidator(true, null, $cc);
            return $this->json ? json_encode($validation) : $validation;
        } catch (Exception $e) {
            $validation = $this->manageExceptionIban($e);
            return $this->json ? json_encode($validation) : $validation;
        }
    }

    private function manageExceptionIban(Exception $e) {
        switch ($e->getCode()) {
        case Codes::get('ERROR_VALIDATOR_CREDITCARD_LENGTH_NOT_VALID'):
            DelegateUtility::eccezione($e, $this, 'ERRON_VALIDATE_LENGTH');
            return new ObjValidator(false, TranslatorUtility::__translate('ERRON_VALIDATE_LENGTH', $this->localefile));
        case Codes::get('ERROR_VALIDATOR_CREDITCARD_EXPIRED'):
            DelegateUtility::eccezione($e, $this, 'ERRON_VALIDATE_EXPIRATION');
            return new ObjValidator(false, TranslatorUtility::__translate('ERRON_VALIDATE_EXPIRATION', $this->localefile));
        case Codes::get('ERROR_VALIDATOR_CREDITCARD_CVC_NOT_VALID'):
            DelegateUtility::eccezione($e, $this, 'ERRON_VALIDATE_CVC');
            return new ObjValidator(false, TranslatorUtility::__translate('ERRON_VALIDATE_CVC', $this->localefile));
        default:
            DelegateUtility::eccezione($e, $this, "ERRON_VALIDATE");
            return new ObjValidator(false, TranslatorUtility::__translate('ERRON_VALIDATE_NOT_VALID', $this->localefile));
        }
    }

    function tpcreditcard() {
        $this->LOG_FUNCTION = "tpcc";
        try {
            $ccs = CreditcardUtility::getAvailableTypes();
            $result = array();
            $obj = null;
            foreach ($ccs as $cc) {
                $url = WWW_ROOT . "img" . DS . "creditcards" . DS . "" . $cc['type'] . ".png";
                $obj = array(
                    "cod" => $cc['type'],
                    "title" => TranslatorUtility::__translate($cc['type'], $this->localefile),
                    "symbol" => "",
                    "flgused" => 1,
                    "iconimage" => "data: " . mime_content_type($url) . ";base64," . FileUtility::getBaseContentByPath($url),
                );
                array_push($result, $obj);
            }
            $this->ok();
            return $this->json ? json_encode($result, true) : $result;
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }
}
