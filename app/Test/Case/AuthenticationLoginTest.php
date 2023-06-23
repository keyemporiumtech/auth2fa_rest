<?php

/*
 * Suite di test per la gestione del login del modulo authentication 
 */
class AuthenticationUtilityTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('authentication->Login');
		$suite->addTestDirectory(dirname(__FILE__) . '/authentication/login/');
		return $suite;
	}
}