<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Workrole", "Model");

class WorkroleBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Workrole');
	}
}
