<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Basket", "Model");

class BasketBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Basket');
	}
}
