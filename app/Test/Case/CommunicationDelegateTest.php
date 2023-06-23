<?php

/*
 * Suite di test per il delegate del modulo communication
 */
class CommunicationDelegateTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('communication->Delegate');
		$suite->addTestDirectory(dirname(__FILE__) . '/communication/delegate/');
		return $suite;
	}
}
