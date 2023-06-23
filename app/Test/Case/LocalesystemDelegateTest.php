<?php

/*
 * Suite di test per il delegate del modulo localesystem
 */
class LocalesystemDelegateTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('localesystem->Delegate');
		$suite->addTestDirectory(dirname(__FILE__) . '/localesystem/delegate/');
		return $suite;
	}
}
