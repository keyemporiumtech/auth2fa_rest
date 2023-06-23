<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Ticket", "Model");

class TicketBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Ticket');
	}
}
