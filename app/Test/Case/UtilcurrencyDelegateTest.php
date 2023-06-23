<?php

/*
 * Suite di test per il delegate del modulo util_currency
 */
class UtilcurrencyDelegateTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('util_currency->Delegate');
		$suite->addTestDirectory(dirname(__FILE__) . '/util_currency/delegate/');
		return $suite;
	}
}
