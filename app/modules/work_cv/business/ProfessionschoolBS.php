<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Professionschool", "Model");

class ProfessionschoolBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Professionschool');
	}
}
