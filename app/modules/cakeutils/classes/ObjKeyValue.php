<?php

/**
 * Classe che definisce un oggetto chiave-valore
 * 
 * @author Giuseppe Sassone
 *
 */
class ObjKeyValue {
	public $key;
	public $value;

	function __construct($k, $v) {
		$this->key= $k;
		$this->value= $v;
	}
}
