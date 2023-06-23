<?php

/*
 * Suite di test per il modulo localesystem
 */
class LocalesystemTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('localesystem');
		$suite->addTestDirectory(dirname(__FILE__) . '/localesystem/');
		$suite->addTestDirectory(dirname(__FILE__) . '/localesystem/business/');
		$suite->addTestDirectory(dirname(__FILE__) . '/localesystem/delegate/');
		$suite->addTestDirectory(dirname(__FILE__) . '/localesystem/controller/');
		return $suite;
	}
}
