<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Servicetax", "Model");

class ServicetaxBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Servicetax');
	}
}
