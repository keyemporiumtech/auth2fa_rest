<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Pocketreservesetting", "Model");

class PocketreservesettingBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Pocketreservesetting');
	}
}
