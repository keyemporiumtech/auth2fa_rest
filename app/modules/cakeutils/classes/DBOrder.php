<?php
App::uses("EnumQueryType", "modules/cakeutils/config");

class DBOrder {	
	public $type;
	public $key;
	public $value;	
	
	function __construct(){
		$this->type = EnumQueryType::ORDER;
	}

}