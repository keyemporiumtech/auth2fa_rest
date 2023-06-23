<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Mail", "Model");

class MailBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Mail');
	}
}