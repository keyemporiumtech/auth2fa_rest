<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Attachment", "Model");

class AttachmentBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Attachment');
	}
}