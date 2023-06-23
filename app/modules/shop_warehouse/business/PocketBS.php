<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Pocket", "Model");

class PocketBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Pocket');
	}
}
