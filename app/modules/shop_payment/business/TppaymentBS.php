<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Tppayment", "Model");

class TppaymentBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Tppayment');
	}
}
