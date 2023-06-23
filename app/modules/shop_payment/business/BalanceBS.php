<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Balance", "Model");

class BalanceBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Balance');
	}
}
