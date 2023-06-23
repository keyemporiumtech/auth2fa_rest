<?php
// model
App::uses("TimezoneDto", "modules/localesystem/classes");
//delegate
App::uses("AppGenericUI", "modules/cakeutils/classes");

class LocaleUI extends AppGenericUI {
	public $json= false;

	function __construct() {
		parent::__construct("LocaleUI");
	}

	function timezone() {
		$this->LOG_FUNCTION= "timezone";
		$timezone= new TimezoneDto();
		$timezone->name= date_default_timezone_get();
		$timezone->value= date('P');
		if ($this->json) {
			return json_encode($timezone);
		}
		return $timezone;
	}
}