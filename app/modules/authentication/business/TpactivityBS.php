<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Tpactivity", "Model");

class TpactivityBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Tpactivity');
	}
}