<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Mimetype", "Model");

class MimetypeBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Mimetype');
	}
}