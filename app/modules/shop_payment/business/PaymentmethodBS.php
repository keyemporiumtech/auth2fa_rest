<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Paymentmethod", "Model");

class PaymentmethodBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Paymentmethod');
	}
}
