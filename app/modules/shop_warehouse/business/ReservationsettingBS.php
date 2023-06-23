<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Reservationsetting", "Model");

class ReservationsettingBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Reservationsetting');
	}
}
