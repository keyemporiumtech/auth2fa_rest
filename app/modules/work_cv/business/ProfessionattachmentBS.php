<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Professionattachment", "Model");

class ProfessionattachmentBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Professionattachment');
	}
}
