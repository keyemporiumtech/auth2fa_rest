<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Product", "Model");

class ProductBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Product');
	}
}
