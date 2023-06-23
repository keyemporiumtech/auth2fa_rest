<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Mailreceiver", "Model");

class MailreceiverBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Mailreceiver');
	}
}