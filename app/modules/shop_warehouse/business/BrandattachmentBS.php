<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Brandattachment", "Model");

class BrandattachmentBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Brandattachment');
	}
}
