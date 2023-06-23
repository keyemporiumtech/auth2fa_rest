<?php

/*
 * Suite di test per il business del modulo cakeutils
 */
class CakeutilsBusinessTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('cakeutils->Business');
		$suite->addTestDirectory(dirname(__FILE__) . '/cakeutils/business/');
		return $suite;
	}
}