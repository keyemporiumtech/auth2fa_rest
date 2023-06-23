<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Contactreference", "Model");

class ContactreferenceBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Contactreference');
	}
}
