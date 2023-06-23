<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Activityuser", "Model");

class ActivityuserBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Activityuser');
	}
}
