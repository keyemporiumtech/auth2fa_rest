<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Tpcontactreference", "Model");

class TpcontactreferenceBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Tpcontactreference');
	}
}