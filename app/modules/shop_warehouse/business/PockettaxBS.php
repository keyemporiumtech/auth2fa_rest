<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Pockettax", "Model");

class PockettaxBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Pockettax');
	}
}
