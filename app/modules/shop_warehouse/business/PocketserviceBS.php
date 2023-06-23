<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Pocketservice", "Model");

class PocketserviceBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Pocketservice');
	}
}
