<?php
App::uses("EnumEmptyType", "modules/coreutils/config");
App::uses("EmptyUtility", "modules/coreutils/utility");

class JsonMappingUtility {

    static function mapPropertyOnObject($json, &$object, $fromProperty, $destProperty = null, $defaultValue = null, $emptyExclusions = null) {
        if (empty($destProperty)) {
            $destProperty = $fromProperty;
        }
        if (property_exists($json, $fromProperty) && !EmptyUtility::emptyExclusions($json->$fromProperty, $emptyExclusions)) {
            $object[$destProperty] = $json->$fromProperty;
        } elseif (!EmptyUtility::emptyExclusions($defaultValue, $emptyExclusions)) {
            $object[$destProperty] = $defaultValue;
        }
    }

    static function mapPropertyOnObjectSpecificValue($specificValue, $json, &$object, $fromProperty, $destProperty = null, $defaultValue = null, $emptyExclusions = null) {
        if (empty($destProperty)) {
            $destProperty = $fromProperty;
        }
        if (property_exists($json, $fromProperty) && !EmptyUtility::emptyExclusions($json->$fromProperty, $emptyExclusions)) {
            $object[$destProperty] = $specificValue;
        } elseif (!EmptyUtility::emptyExclusions($defaultValue, $emptyExclusions)) {
            $object[$destProperty] = $defaultValue;
        }
    }

    static function mapPropertyBase64OnObject($json, &$object, $fromProperty, $destProperty = null, $defaultValue = null, $emptyExclusions = null) {
        if (empty($destProperty)) {
            $destProperty = $fromProperty;
        }
        if (property_exists($json, $fromProperty) && !EmptyUtility::emptyExclusions($json->$fromProperty, $emptyExclusions)) {
            $object[$destProperty] = str_replace(' ', '+', $json->$fromProperty);
        } elseif (!EmptyUtility::emptyExclusions($defaultValue, $emptyExclusions)) {
            $object[$destProperty] = $defaultValue;
        }
    }
}
