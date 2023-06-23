<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Userrelationpermission", "Model");

class UserrelationpermissionBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Userrelationpermission');
	}
}
