<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Workskill", "Model");

class WorkskillBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Workskill');
	}
}
