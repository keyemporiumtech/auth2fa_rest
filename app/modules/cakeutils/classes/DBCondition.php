<?php
App::uses("EnumQueryType", "modules/cakeutils/config");

class DBCondition {	
	public $type;
	public $operator; // EnumQueryOperator
	public $key;
	public $value;
	public $sign; // EnumQuerySign
	public $like; // EnumQueryLike
	public $between=array();
	public $children = array(); // DBCondition
	
	function __construct(){
		$this->type = EnumQueryType::CONDITION;
	}

}