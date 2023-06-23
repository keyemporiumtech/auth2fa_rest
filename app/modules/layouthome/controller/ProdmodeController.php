<?php
App::uses("AppController", "Controller");
App::uses('ProductionVisibilityUtility', 'modules/layouthome/utility');

class ProdmodeController extends AppController {

	public function login() {
	}

	public function verify($username = null, $password = null) {
		parent::evalParam($username, "username");
		parent::evalParam($password, "password");
		if (ProductionVisibilityUtility::login($username, $password)) {
			$this->redirect(array (
					"action" => "",
					"controller" => "pages" 
			));
		} else {
			$this->redirect(array (
					"action" => "noauth" 
			));
		}
	}

	public function logout() {
		ProductionVisibilityUtility::logout();
		$this->redirect(array (
				"action" => "",
				"controller" => "pages" 
		));
	}

	public function noauth() {
	}
	
	public function layouts() {
	}
}