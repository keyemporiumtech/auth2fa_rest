<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Tpuserrelation", "Model");

class TpuserrelationBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Tpuserrelation');
	}
}