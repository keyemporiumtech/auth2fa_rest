<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Testfk", "Model");

class TestfkBS extends AppGenericBS {
	
	function __construct(){
		parent::__construct('Testfk');
	}
	
	
}