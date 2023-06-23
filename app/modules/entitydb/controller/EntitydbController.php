<?php
App::uses('AppController', 'Controller');
App::uses('Test', 'Model');

class EntitydbController extends AppController {
	
	public function home() {
		
	}

	public function exampleFirst() {
		$query= new Test();
		$this->set("record", $query->find('first'));
	}
	
	public function exampleAll() {
		$query= new Test();
		$this->set("records", $query->find('all'));
	}
}