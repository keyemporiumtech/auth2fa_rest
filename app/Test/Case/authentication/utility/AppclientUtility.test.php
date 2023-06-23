<?php
App::uses("AppclientUtility", "modules/authentication/utility");

class AppclientUtilityTest extends CakeTestCase {

	function testToken() {
		$clientID= "myclientID";
		$token= AppclientUtility::buildToken($clientID);
		$this->assertEquals(AppclientUtility::verifyToken($token, $clientID), true);
	}
	
}