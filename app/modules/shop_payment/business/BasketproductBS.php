<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Basketproduct", "Model");

class BasketproductBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Basketproduct');
	}
}
