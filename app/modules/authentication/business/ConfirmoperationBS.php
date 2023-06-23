<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Confirmoperation", "Model");

class ConfirmoperationBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Confirmoperation');
	}
}