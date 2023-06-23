<?php
App::uses("EnumQueryType", "modules/cakeutils/config");

class DBPaginate {	
	public $type;
	public $limit;
	public $page;	
	
	function __construct(){
		$this->type = EnumQueryType::PAGINATE;
		$this->limit = 0;
		$this->page = 1;
	}

}