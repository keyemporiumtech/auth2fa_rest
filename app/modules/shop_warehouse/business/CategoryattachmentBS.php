<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Categoryattachment", "Model");

class CategoryattachmentBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Categoryattachment');
	}
}
