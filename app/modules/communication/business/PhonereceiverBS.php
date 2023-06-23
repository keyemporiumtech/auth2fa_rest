<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Phonereceiver", "Model");

class PhonereceiverBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Phonereceiver');
	}
}