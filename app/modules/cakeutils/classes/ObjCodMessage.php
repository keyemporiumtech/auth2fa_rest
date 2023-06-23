<?php
App::uses("EnumMessageType", "modules/cakeutils/config");

/**
 * Classe che definisce un oggetto di tipo messaggio
 * 
 * @author Giuseppe Sassone
 *
 */
class ObjCodMessage {
	public $cod;
	public $message;
	public $type; // EnumMessageType
	public $category;
	// example INTERNAL, EXCEPTION, MESSAGGE
	function __construct($k, $v, $type= null, $category= null) {
		$this->cod= $k;
		$this->message= $v;
		$this->type= $type;
	}

	function printType() {
		switch ($this->type) {
			case EnumMessageType::INFO :
				return "INFO";
			case EnumMessageType::WARNING :
				return "WARNING";
			case EnumMessageType::EXCEPTION :
				return "EXCEPTION";
			case EnumMessageType::ERROR :
				return "ERROR";
			default :
				return "GENERIC";
		}
	}

	function printCategory() {
		return empty($this->category) ? "INFORMATION" : $this->category;
	}
}
