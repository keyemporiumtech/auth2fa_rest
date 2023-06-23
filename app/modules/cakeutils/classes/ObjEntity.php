<?php
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
App::uses("JsonMappingUtility", "modules/cakeutils/utility");

/**
 * Classe che definisce un oggetto da usare per la gestione delle entity.
 * Principalmente usato nella conversione da json a entity (@see JsonMappingUtility)
 *
 * @author Giuseppe Sassone
 *
 */
class ObjEntity {
    public $properties = array(); // array di ObjPropertyEntity
    public $value;
    public $name;

    function __construct($n, $v, $p = array()) {
        $this->name = $n;
        $this->value = $v;
        $this->properties = $p;
    }

    function mapInstance($instance, $edit = false) {
        foreach ($this->properties as $property) {
            if ($property instanceof ObjPropertyEntity) {
                if ($edit) {
                    $property->default = null;
                }
                JsonMappingUtility::mapPropertyOnObject($this->value, $instance[$this->name], $property->from, $property->dest, $property->default, $property->emptyExclusions);
            }
        }
        return $instance;
    }
}
