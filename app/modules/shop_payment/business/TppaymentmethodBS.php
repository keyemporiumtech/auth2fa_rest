<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Tppaymentmethod", "Model");

class TppaymentmethodBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Tppaymentmethod');
	}
}
