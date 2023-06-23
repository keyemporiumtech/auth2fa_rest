<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Activity", "Model");

class ActivityBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Activity');
	}
}
