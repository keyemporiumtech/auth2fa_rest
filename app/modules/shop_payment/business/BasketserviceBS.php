<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Basketservice", "Model");

class BasketserviceBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Basketservice');
	}
}
