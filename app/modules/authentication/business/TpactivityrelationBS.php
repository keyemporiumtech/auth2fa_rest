<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Tpactivityrelation", "Model");

class TpactivityrelationBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Tpactivityrelation');
	}
}