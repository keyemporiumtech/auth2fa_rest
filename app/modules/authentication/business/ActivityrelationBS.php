<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Activityrelation", "Model");

class ActivityrelationBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Activityrelation');
	}
}
