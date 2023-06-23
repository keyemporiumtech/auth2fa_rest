<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Service", "Model");

class ServiceBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Service');
	}
}
