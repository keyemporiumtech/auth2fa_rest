<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Tpaddress", "Model");

class TpaddressBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Tpaddress');
	}
}