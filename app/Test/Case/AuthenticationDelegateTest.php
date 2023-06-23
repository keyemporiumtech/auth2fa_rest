<?php

/*
 * Suite di test per il delegate del modulo authentication
 */
class AuthenticationDelegateTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('authentication->Delegate');
		$suite->addTestDirectory(dirname(__FILE__) . '/authentication/delegate/');
		return $suite;
	}
}
