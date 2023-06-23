<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Workuser", "Model");

class WorkuserBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Workuser');
	}
}
