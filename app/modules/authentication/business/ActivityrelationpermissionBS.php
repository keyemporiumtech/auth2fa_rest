<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Activityrelationpermission", "Model");

class ActivityrelationpermissionBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Activityrelationpermission');
	}
}
