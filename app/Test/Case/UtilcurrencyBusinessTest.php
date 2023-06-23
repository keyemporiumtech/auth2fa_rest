<?php

/*
 * Suite di test per il business del modulo util_currency
 */
class UtilcurrencyBusinessTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('util_currency->Business');
		$suite->addTestDirectory(dirname(__FILE__) . '/util_currency/business/');
		return $suite;
	}
}
