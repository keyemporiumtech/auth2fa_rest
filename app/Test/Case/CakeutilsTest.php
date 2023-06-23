<?php

/*
 * Suite di test per il modulo cakeutils
 */
class CakeutilsTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('cakeutils');
		$suite->addTestDirectory(dirname(__FILE__) . '/cakeutils/');
		$suite->addTestDirectory(dirname(__FILE__) . '/cakeutils/business/');
		$suite->addTestDirectory(dirname(__FILE__) . '/cakeutils/businessexecution/');
		$suite->addTestDirectory(dirname(__FILE__) . '/cakeutils/delegate/');
		$suite->addTestDirectory(dirname(__FILE__) . '/cakeutils/controller/');
		return $suite;
	}
}