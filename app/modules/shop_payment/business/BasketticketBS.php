<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Basketticket", "Model");

class BasketticketBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Basketticket');
	}
}
