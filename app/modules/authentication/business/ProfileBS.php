<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Profile", "Model");

class ProfileBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Profile');
	}
}
