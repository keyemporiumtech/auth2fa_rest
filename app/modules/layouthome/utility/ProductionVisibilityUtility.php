<?php
App::uses("AppController", "Controller");
App::uses("Enables", "Config/system");

class ProductionVisibilityUtility {

	static function login($username, $password) {
		if (empty($username) || empty($password)) {
			return false;
		}
		$token= ProductionVisibilityUtility::getToken($username, $password);
		if (ProductionVisibilityUtility::verifyLogin($token)) {
			CakeSession::write("production-login", $token);
			return true;
		}
		return false;
	}

	static function logout() {
		CakeSession::delete("production-login");
	}

	static function verifyLogin($token= null) {
		if (empty($token)) {
			$token= CakeSession::read("production-login");
		}
		if (empty($token)) {
			return false;
		}
		$tokens= Enables::tokens();
		if (! empty($tokens)) {
			foreach ( $tokens as $tk ) {
				if ($tk == $token) {
					return true;
				}
			}
		}
		return false;
	}

	static function getToken($username, $password) {
		return base64_encode("{$username}:{$password}");
	}

	static function checkAuth(AppController $controller) {
		if (Enables::isProd() && ! ProductionVisibilityUtility::verifyLogin()) {
			$controller->goToPage("noauth", "prodmode");
		}
	}
}