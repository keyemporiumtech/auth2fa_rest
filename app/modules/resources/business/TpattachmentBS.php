<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Tpattachment", "Model");

class TpattachmentBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Tpattachment');
	}
}