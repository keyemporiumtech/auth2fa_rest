<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Permission", "Model");

class PermissionBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Permission');
	}
}
