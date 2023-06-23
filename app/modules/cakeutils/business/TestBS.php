<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Test", "Model");

class TestfkBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Test');
	}
}