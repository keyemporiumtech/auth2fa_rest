<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Brand", "Model");

class BrandBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Brand');
	}
}
