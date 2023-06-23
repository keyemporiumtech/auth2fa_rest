<?php
App::uses("EnumEmptyType", "modules/coreutils/config");
App::uses("ArrayUtility", "modules/cakeutils/utility");

class EmptyUtility {

    static function emptyExclusions($value, $exclusions = array()) {
        if (!ArrayUtility::isEmpty($exclusions)) {
            foreach ($exclusions as $exclusion) {
                if (!EmptyUtility::emptyExclusion($value, $exclusion)) {
                    return false;
                }
            }
        }
        return empty($value);
    }

    /* type EnumEmptyType*/
    static function emptyExclusion($value, $type = null) {
        switch ($type) {
        case EnumEmptyType::EXCLUDE_ARRAY:
            if (is_array($value) && count($value) == 0) {
                return false;
            } else {
                return empty($value);
            }
            break;
        case EnumEmptyType::EXCLUDE_FALSE:
            if ($value == FALSE || $value == false) {
                return false;
            } else {
                return empty($value);
            }
            break;
        case EnumEmptyType::EXCLUDE_NULL:
            if ($value == NULL || $value == null) {
                return false;
            } else {
                return empty($value);
            }
            break;
        case EnumEmptyType::EXCLUDE_NUMBER:
            if ($value == "0" || $value == '0' || $value == 0) {
                return false;
            } else {
                return empty($value);
            }
            break;
        case EnumEmptyType::EXCLUDE_DECIMAL:
            if ($value == "0.00" || $value == '0.00' || $value == 0.00) {
                return false;
            } else {
                return empty($value);
            }
            break;
        case EnumEmptyType::EXCLUDE_STRING:
            if ($value == "" || $value == '') {
                return false;
            } else {
                return empty($value);
            }
            break;
        default:
            return empty($value);
        }
    }
}