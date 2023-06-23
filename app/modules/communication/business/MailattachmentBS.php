<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Mailattachment", "Model");

class MailattachmentBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Mailattachment');
	}
}