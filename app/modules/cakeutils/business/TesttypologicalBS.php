<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Testtypological", "Model");

class TesttypologicalBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Testtypological');
	}
}