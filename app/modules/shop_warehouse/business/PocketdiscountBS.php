<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Pocketdiscount", "Model");

class PocketdiscountBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Pocketdiscount');
	}
}
