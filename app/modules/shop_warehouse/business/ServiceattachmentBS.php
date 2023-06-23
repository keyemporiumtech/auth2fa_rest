<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Serviceattachment", "Model");

class ServiceattachmentBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Serviceattachment');
	}
}
