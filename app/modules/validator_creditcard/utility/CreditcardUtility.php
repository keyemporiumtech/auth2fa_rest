<?php
App::uses("Codes", "Config/system");
App::uses("ArrayUtility", "modules/coreutils/utility");
App::uses("CreditcardValidator", "modules/validator_creditcard/classes");
App::uses("CreditcardModel", "modules/validator_creditcard/classes");
App::uses("CreditcardRequest", "modules/validator_creditcard/classes");

class CreditcardUtility {

    static function getAvailableTypes() {
        return CreditcardValidator::$cards;
    }

    static function getCreditcardModel(CreditcardRequest $request) {
        $model = null;
        if (!empty($request) && empty($request->type) && !empty($request->number)) {
            $request->type = CreditcardValidator::creditCardType($request->number);
        }

        if (empty($request)) {
            throw new Exception("request is null", Codes::get('ERROR_VALIDATOR_CREDITCARD'));
        } elseif (!empty($request) && !ArrayUtility::isEmpty($request->notFilled())) {
            throw new Exception("request params [" . ArrayUtility::getStringByList($request->notFilled()) . "] are null", Codes::get('ERROR_VALIDATOR_CREDITCARD'));
        }

        $card = CreditcardValidator::$cards[$request->type];
        if (empty($card)) {
            throw new Exception("type {$request->type} not exist", Codes::get('ERROR_VALIDATOR_CREDITCARD_TYPE_NOT_FOUND'));
        }

        $model = new CreditcardModel();
        $model->input = $request;
        $model->pattern = $card['pattern'];
        $model->format = array_key_exists('format', $card) ? $card['format'] : null;
        $model->length = $card['length'];
        $model->cvcLength = $card['cvcLength'];
        $model->luhn = $card['luhn'];

        $NUMBER = str_replace(" ", "", $request->number);
        $TYPE = $card['type'];

        if (!CreditcardValidator::validPattern($NUMBER, $TYPE)) {
            throw new Exception("pattern " . $card['pattern'] . " not respected", Codes::get('ERROR_VALIDATOR_CREDITCARD_PATTERN_NOT_VALID'));
        }
        if (!CreditcardValidator::validLength($NUMBER, $TYPE)) {
            throw new Exception("length " . ArrayUtility::getStringByList($card['length']) . " not respected by " . strlen($NUMBER), Codes::get('ERROR_VALIDATOR_CREDITCARD_LENGTH_NOT_VALID'));
        }
        if (!CreditcardValidator::validLuhn($NUMBER, $TYPE)) {
            throw new Exception("luhn not valid", Codes::get('ERROR_VALIDATOR_CREDITCARD_LUHN_NOT_VALID'));
        }
        if (!CreditcardValidator::validDate($request->expireYY, $request->expireMM)) {
            throw new Exception("expiration " . $request->expireMM . "/" . $request->expireYY . " not valid", Codes::get('ERROR_VALIDATOR_CREDITCARD_EXPIRED'));
        }
        if (!CreditcardValidator::validCvc($request->cvc, $TYPE)) {
            throw new Exception("cvc " . $request->cvc . " not valid", Codes::get('ERROR_VALIDATOR_CREDITCARD_CVC_NOT_VALID'));
        }

        $model->number = $NUMBER;
        $model->type = $TYPE;
        $model->validNumber = true;
        $model->validDate = true;
        $model->validCvc = true;

        return $model;

    }
}