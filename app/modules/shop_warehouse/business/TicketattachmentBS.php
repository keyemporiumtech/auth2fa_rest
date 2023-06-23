<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Ticketattachment", "Model");

class TicketattachmentBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Ticketattachment');
	}
}
