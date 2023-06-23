<?php
App::uses("EnumEmptyType", "modules/coreutils/config");
/**
 * Classe che definisce informazioni generica di una proprietà di una entity.
 * Principalmente usato nella conversione da json a entity (@see JsonMappingUtility)
 *
 * @author Giuseppe Sassone
 *
 */
class ObjPropertyEntity {
    public $from;
    public $dest;
    public $default;
    public $emptyExclusions; // array di EnumEmptyType

    function __construct($f, $d = null, $v = null, $excludeEmpties = null) {
        $this->from = $f;
        $this->dest = $d;
        $this->default = $v;
        if (empty($excludeEmpties)) {
            $this->emptyExclusions = array();
            $this->fillExclusionsByDefault();
        }
    }

    private function fillExclusionsByDefault() {
        if (is_bool($this->default)) {
            array_push($this->emptyExclusions, EnumEmptyType::EXCLUDE_FALSE);
        } elseif (is_float($this->default)) {
            array_push($this->emptyExclusions, EnumEmptyType::EXCLUDE_DECIMAL);
        } elseif (is_numeric($this->default)) {
            array_push($this->emptyExclusions, EnumEmptyType::EXCLUDE_NUMBER);
        } elseif (is_array($this->default)) {
            array_push($this->emptyExclusions, EnumEmptyType::EXCLUDE_ARRAY);
        } elseif (is_string($this->default)) {
            array_push($this->emptyExclusions, EnumEmptyType::EXCLUDE_STRING);
        }
    }
}
