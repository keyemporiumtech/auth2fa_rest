<?php
App::uses('AppController', 'Controller');
App::uses('EnumQueryLike', 'modules/cakeutils/config');
App::uses('EnumQuerySign', 'modules/cakeutils/config');
App::uses('EnumQueryOperator', 'modules/cakeutils/config');
App::uses('EnumQueryType', 'modules/cakeutils/config');
App::uses("TestfkBS", "modules/cakeutils/business");

class CakeutilsbsController extends AppController {

	public function addFields() {
		$bs= new TestfkBS();
		$bs->addFields(array (
				'id',
				'cod' 
		));
		$this->set("unique", $bs->unique(1));
	}

	public function addCondition() {
		$bs= new TestfkBS();
		$bs->addCondition("cod", "FK002");
		$this->set("unique", $bs->unique());
	}

	public function addBelongsTo() {
		$bs= new TestfkBS();
		$bs->addBelongsTo("test_fk");
		$bs->addCondition("test_fk.cod", "ENTITY001");
		$this->set("unique", $bs->unique());
	}
	
	public function addLike() {
		$bs= new TestfkBS();
		$bs->addLike("cod", "001", EnumQueryLike::LEFT);		
		$this->set("all", $bs->all());
	}
	
	public function addSign() {
		$bs= new TestfkBS();
		$bs->addSign("id", "1", EnumQuerySign::GREATER);
		$this->set("all", $bs->all());
	}
	
	public function addBetween() {
		$bs= new TestfkBS();
		$bs->addBetween("id", "1", "2");
		$this->set("all", $bs->all());
	}
}