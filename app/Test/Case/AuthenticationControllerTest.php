<?php

/*
 * Suite di test per il controller del modulo authentication
 */
class AuthenticationControllerTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('authentication->Controller');
		$suite->addTestDirectory(dirname(__FILE__) . '/authentication/controller/');
		return $suite;
	}
}
