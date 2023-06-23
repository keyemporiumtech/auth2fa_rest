<?php

/*
 * Suite di test per il modulo util_currency
 */
class UtilcurrencyTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('util_currency');
		$suite->addTestDirectory(dirname(__FILE__) . '/util_currency/');
		$suite->addTestDirectory(dirname(__FILE__) . '/util_currency/business/');
		$suite->addTestDirectory(dirname(__FILE__) . '/util_currency/delegate/');
		$suite->addTestDirectory(dirname(__FILE__) . '/util_currency/controller/');
		return $suite;
	}
}
