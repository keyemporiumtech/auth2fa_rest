<?php
app::uses("IBANModel", "modules/validator_iban/classes");
app::uses("EnumIBANOrg", "modules/validator_iban/classes");
app::uses("TranslatorUtility", "modules/cakeutils/utility");
app::uses("ArrayUtility", "modules/coreutils/utility");

class IBANGeneric {
    static $TRANSLATOR_FILE = "iban";

    static function setLabels(&$arr, $index, $key) {
        $arr[$index] = TranslatorUtility::__translate($key, "iban");
    }

    static function setLabelsPatternByModel(IBANModel &$model, $separator1 = '|', $separator2 = '-') {
        $values = explode($separator1, $model->pattern);
        if (!ArrayUtility::isEmpty($values)) {
            foreach ($values as $value) {
                $elements = explode($separator2, $value);
                if (!ArrayUtility::isEmpty($elements) && count($elements) == 3) {
                    if ($elements[0] != 'bankorg') {
                        $model->labels[$elements[0]] = TranslatorUtility::__translate(strtoupper($elements[0]), IBANGeneric::$TRANSLATOR_FILE);
                    } else {
                        switch ($model->labelorg_cod) {
                        case EnumIBANOrg::BRANCH:
                            $model->labels[$elements[0]] = TranslatorUtility::__translate('COD_BRANCH', IBANGeneric::$TRANSLATOR_FILE);
                            break;
                        case EnumIBANOrg::AGENCY:
                            $model->labels[$elements[0]] = TranslatorUtility::__translate('COD_AGENCY', IBANGeneric::$TRANSLATOR_FILE);
                            break;
                        case EnumIBANOrg::FRONT_OFFICE:
                            $model->labels[$elements[0]] = TranslatorUtility::__translate('COD_FRONT_OFFICE', IBANGeneric::$TRANSLATOR_FILE);
                            break;
                        case EnumIBANOrg::GUICHET:
                            $model->labels[$elements[0]] = TranslatorUtility::__translate('COD_GUICHET', IBANGeneric::$TRANSLATOR_FILE);
                            break;
                        default:
                            break;
                        }
                    }
                }
            }
        }
    }
}