<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Address", "Model");

class AddressBS extends AppGenericBS {
	
	function __construct(){
		parent::__construct('Address');
	}
	
	
}