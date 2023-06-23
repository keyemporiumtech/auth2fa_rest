<?php

/*
 * Suite di test per il business del modulo localesystem
 */
class LocalesystemBusinessTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('localesystem->Business');
		$suite->addTestDirectory(dirname(__FILE__) . '/localesystem/business/');
		return $suite;
	}
}
