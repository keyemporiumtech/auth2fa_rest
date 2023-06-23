<?php
App::uses("MathUtility", "modules/coreutils/utility");
class AmountUtility {

    static function setFieldFloat(&$data, $className, $fields = array()) {
        if (array_key_exists($className, $data)) {
            foreach ($fields as $field) {
                $data[$className][$field] = MathUtility::getStringByDouble($data[$className][$field], 2);
            }
        }
    }
}
