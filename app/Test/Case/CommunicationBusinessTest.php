<?php

/*
 * Suite di test per il business del modulo communication
 */
class CommunicationBusinessTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('communication->Business');
		$suite->addTestDirectory(dirname(__FILE__) . '/communication/business/');
		return $suite;
	}
}
