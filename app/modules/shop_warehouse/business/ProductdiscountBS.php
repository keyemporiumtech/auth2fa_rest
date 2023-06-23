<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Productdiscount", "Model");

class ProductdiscountBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Productdiscount');
	}
}
