<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Tpcat", "Model");

class TpcatBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Tpcat');
	}
}