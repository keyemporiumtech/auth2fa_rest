<?php

/*
 * Suite di test per il business del modulo authentication
 */
class AuthenticationBusinessTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('authentication->Business');
		$suite->addTestDirectory(dirname(__FILE__) . '/authentication/business/');
		return $suite;
	}
}
