<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Price", "Model");

class PriceBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Price');
	}
}
