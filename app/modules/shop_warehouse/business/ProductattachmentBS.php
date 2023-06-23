<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Productattachment", "Model");

class ProductattachmentBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Productattachment');
	}
}
