<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Userreport", "Model");
App::uses("SystemUtility", "modules/coreutils/utility");

class UserreportBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Userreport');
	}

	function storeOperation($id_user, $cod, $message) {
		$sessionId= CakeSession::id();
		$browser= SystemUtility::browser();
		$this->acceptNull= true;
		$userreport= $this->instance();
		$userreport ['Userreport'] ['codoperation']= $cod;
		$userreport ['Userreport'] ['description']= $message;
		$userreport ['Userreport'] ['sessionid']= $sessionId;
		$userreport ['Userreport'] ['ip']= SystemUtility::getIPClient();
		$userreport ['Userreport'] ['os']= SystemUtility::getPlatormInfo();
		$userreport ['Userreport'] ['browser']= $browser ['name'];
		$userreport ['Userreport'] ['browser_version']= $browser ['version'];
		$userreport ['Userreport'] ['user']= $id_user;
		return $this->save($userreport);
	}
}