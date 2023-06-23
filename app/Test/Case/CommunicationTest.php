<?php

/*
 * Suite di test per il modulo communication
 */
class CommunicationTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('communication');
		$suite->addTestDirectory(dirname(__FILE__) . '/communication/');
		$suite->addTestDirectory(dirname(__FILE__) . '/communication/business/');
		$suite->addTestDirectory(dirname(__FILE__) . '/communication/delegate/');
		$suite->addTestDirectory(dirname(__FILE__) . '/communication/controller/');
		return $suite;
	}
}
