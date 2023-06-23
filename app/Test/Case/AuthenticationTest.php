<?php

/*
 * Suite di test per il modulo authentication
 */
class AuthenticationTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('authentication');
		$suite->addTestDirectory(dirname(__FILE__) . '/authentication/');
		$suite->addTestDirectory(dirname(__FILE__) . '/authentication/business/');
		$suite->addTestDirectory(dirname(__FILE__) . '/authentication/delegate/');
		$suite->addTestDirectory(dirname(__FILE__) . '/authentication/controller/');
		return $suite;
	}
}
