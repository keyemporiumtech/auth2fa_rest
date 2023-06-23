<?php
App::uses("AppController", "Controller");

class CakeController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
		$this->layout= "cake";
	}

	public function home() {
	}
}