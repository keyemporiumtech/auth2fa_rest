<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Pocketattachment", "Model");

class PocketattachmentBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Pocketattachment');
	}
}
