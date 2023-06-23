<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Ticketreservesetting", "Model");

class TicketreservesettingBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Ticketreservesetting');
	}
}
