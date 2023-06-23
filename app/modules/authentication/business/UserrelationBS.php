<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Userrelation", "Model");

class UserrelationBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Userrelation');
	}
}
