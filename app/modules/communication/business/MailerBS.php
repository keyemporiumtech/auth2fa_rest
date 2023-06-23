<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Mailer", "Model");

class MailerBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Mailer');
	}
}