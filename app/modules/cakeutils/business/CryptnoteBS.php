<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Cryptnote", "Model");

class CryptnoteBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Cryptnote');
	}
}
