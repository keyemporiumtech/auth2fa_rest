<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Group", "Model");

class GroupBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Group');
	}
}
