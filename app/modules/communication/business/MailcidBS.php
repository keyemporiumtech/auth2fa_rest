<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Mailcid", "Model");

class MailcidBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Mailcid');
	}
}