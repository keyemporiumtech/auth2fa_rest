<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Category", "Model");

class CategoryBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Category');
	}
}
