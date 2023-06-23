<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Profession", "Model");

class ProfessionBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Profession');
	}
}
