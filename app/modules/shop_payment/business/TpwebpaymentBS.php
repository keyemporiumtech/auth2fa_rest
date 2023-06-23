<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Tpwebpayment", "Model");

class TpwebpaymentBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Tpwebpayment');
	}
}
