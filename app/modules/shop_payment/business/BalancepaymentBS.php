<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Balancepayment", "Model");

class BalancepaymentBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Balancepayment');
	}
}
