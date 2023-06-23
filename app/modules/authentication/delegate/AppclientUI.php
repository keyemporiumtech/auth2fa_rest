<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");

class AppclientUI extends AppGenericUI {
	public $json= false;

	function __construct() {
		parent::__construct("AppclientUI");
		$this->localefile= "appclient";
	}

	function setTokenError($exception= null) {
		$this->LOG_FUNCTION= "setTokenError";
		DelegateUtility::eccezione($exception, $this, "ERROR_CLIENT_TOKEN");
		return "";
	}
	
	function setTokenValid() {
		$this->LOG_FUNCTION= "setTokenValid";
		$this->ok("Il token è valido");
		return "";
	}
}