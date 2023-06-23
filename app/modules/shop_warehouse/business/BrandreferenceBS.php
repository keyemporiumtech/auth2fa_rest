<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Brandreference", "Model");

class BrandreferenceBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Brandreference');
	}
}
