<?php

/*
 * Suite di test per il controller del modulo cakeutils
 */
class CakeutilsControllerTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('cakeutils->Controller');
		$suite->addTestDirectory(dirname(__FILE__) . '/cakeutils/controller/');
		return $suite;
	}
}