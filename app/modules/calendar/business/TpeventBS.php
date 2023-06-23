<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Tpevent", "Model");

class TpeventBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Tpevent');
	}
}
