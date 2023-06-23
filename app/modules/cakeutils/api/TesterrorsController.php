<?php
App::uses("AppController", "Controller");
// delegate
App::uses("ErrorUI", "modules/cakeutils/delegatesss");

class TesterrorsController extends AppController {

	public function beforeFilter() {
		$this->json= true;
		parent::beforeFilter();
		$this->delegate= new ErrorUI();
		$this->delegate->json= $this->json;
	}

	public function test() {
		// errore in import		
	}

	public function testThrow() {
		try {
			echo "Sono nel try<br/>";
		} catch ( Exception $e ) {
			echo "Sono nel catch<br/>";
			debug($e);
		}
	}
}