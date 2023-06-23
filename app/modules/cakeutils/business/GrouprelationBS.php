<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Grouprelation", "Model");

class GrouprelationBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Grouprelation');
	}
}
