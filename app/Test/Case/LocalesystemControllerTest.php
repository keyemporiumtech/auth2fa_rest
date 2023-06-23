<?php

/*
 * Suite di test per il controller del modulo localesystem
 */
class LocalesystemControllerTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('localesystem->Controller');
		$suite->addTestDirectory(dirname(__FILE__) . '/localesystem/controller/');
		return $suite;
	}
}
