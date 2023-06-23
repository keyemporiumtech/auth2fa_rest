<?php

/*
 * Suite di test per il controller del modulo communication
 */
class CommunicationControllerTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('communication->Controller');
		$suite->addTestDirectory(dirname(__FILE__) . '/communication/controller/');
		return $suite;
	}
}
