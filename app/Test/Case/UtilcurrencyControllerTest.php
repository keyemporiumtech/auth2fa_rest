<?php

/*
 * Suite di test per il controller del modulo util_currency
 */
class UtilcurrencyControllerTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('util_currency->Controller');
		$suite->addTestDirectory(dirname(__FILE__) . '/util_currency/controller/');
		return $suite;
	}
}
