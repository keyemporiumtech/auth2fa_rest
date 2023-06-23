<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Useroauthsocial", "Model");

class UseroauthsocialBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Useroauthsocial');
	}
}
