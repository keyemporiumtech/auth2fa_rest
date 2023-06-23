<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("Codes", "Config/system");
App::uses("ObjValidator", "modules/cakeutils/classes");
App::uses("TranslatorUtility", "modules/cakeutils/utility");
App::uses("PasswordUtility", "modules/validator_password/utility");

class PasswordUI extends AppGenericUI {

    function __construct() {
        parent::__construct("PasswordUI");
        $this->localefile = "password";
        $this->obj = null;
    }

    function validate($password = null, $min = 5, $max = 10, $level = 3, $separatorMessage = "<br/>") {
        $this->LOG_FUNCTION = "validate";
        $validation = new ObjValidator();

        try {
            if (empty($password)) {
                DelegateUtility::paramsNull($this, "ERRON_VALIDATE");
                $validation = new ObjValidator(false, TranslatorUtility::__translate('ERRON_VALIDATE_NOT_VALID', $this->localefile));
                return $this->json ? json_encode($validation) : $validation;
            }

            $bool = false;
            $message = "";
            $boolL1 = true;
            $boolL2 = true;
            $boolL3 = true;
            $boolL4 = true;
            if ($level >= 1) {
                $boolL1 = PasswordUtility::validateLength($password, $min, $max);
                if (!$boolL1) {
                    $message .= TranslatorUtility::__translate_args('ERRON_VALIDATE_LENGTH', array($min, $max), $this->localefile) . $separatorMessage;
                }
            }
            if ($level >= 2) {
                $boolL2 = PasswordUtility::validateAlmostNumber($password);
                if (!$boolL2) {
                    $message .= TranslatorUtility::__translate('ERRON_VALIDATE_ALMOST_NUMBER', $this->localefile) . $separatorMessage;
                }
            }
            if ($level >= 3) {
                $boolL3A = PasswordUtility::validateAlmostUpper($password);
                if (!$boolL3A) {
                    $message .= TranslatorUtility::__translate('ERRON_VALIDATE_ALMOST_UPPER', $this->localefile) . $separatorMessage;
                }
                $boolL3B = PasswordUtility::validateAlmostLower($password);
                if (!$boolL3B) {
                    $message .= TranslatorUtility::__translate('ERRON_VALIDATE_ALMOST_LOWER', $this->localefile) . $separatorMessage;
                }
                $boolL3 = $boolL3A && $boolL3B;
            }
            if ($level >= 4) {
                $boolL4 = PasswordUtility::validateAlmostAlpha($password);
                if (!$boolL4) {
                    $message .= TranslatorUtility::__translate('ERRON_VALIDATE_ALMOST_ALPHA', $this->localefile) . $separatorMessage;
                }
            }

            $bool = $boolL1 && $boolL2 && $boolL3 && $boolL4;

            $this->ok();
            $validation = new ObjValidator($bool, $message, null);
            return $this->json ? json_encode($validation) : $validation;
        } catch (Exception $e) {
            $validation = new ObjValidator(false, TranslatorUtility::__translate('ERRON_VALIDATE', $this->localefile));
            return $this->json ? json_encode($validation) : $validation;
        }
    }

}
