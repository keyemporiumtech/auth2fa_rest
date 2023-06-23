<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Pocketproduct", "Model");

class PocketproductBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Pocketproduct');
	}
}
