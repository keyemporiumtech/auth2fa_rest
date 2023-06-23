<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Tickettax", "Model");

class TickettaxBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Tickettax');
	}
}
