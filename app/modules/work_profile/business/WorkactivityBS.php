<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Workactivity", "Model");

class WorkactivityBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Workactivity');
	}
}
