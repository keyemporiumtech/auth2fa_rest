<?php

/*
 * Suite di test per il delegate del modulo cakeutils
 */
class CakeutilsDelegateTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('cakeutils->Delegate');
		$suite->addTestDirectory(dirname(__FILE__) . '/cakeutils/delegate/');
		return $suite;
	}
}