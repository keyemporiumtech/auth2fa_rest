<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Producttax", "Model");

class ProducttaxBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Producttax');
	}
}
