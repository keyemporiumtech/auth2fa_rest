<?php

/*
 * Suite di test per le utility del modulo authentication 
 */
class AuthenticationUtilityTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('authentication->Utility');
		$suite->addTestDirectory(dirname(__FILE__) . '/authentication/utility/');
		return $suite;
	}
}