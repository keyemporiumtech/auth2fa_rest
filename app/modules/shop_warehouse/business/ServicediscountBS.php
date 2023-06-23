<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Servicediscount", "Model");

class ServicediscountBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Servicediscount');
	}
}
