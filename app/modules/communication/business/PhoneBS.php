<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Phone", "Model");

class PhoneBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Phone');
	}
}