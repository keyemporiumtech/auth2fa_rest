<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Ticketdiscount", "Model");

class TicketdiscountBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Ticketdiscount');
	}
}
