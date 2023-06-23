<?php

/*
 * Suite di test per i modulo crypting
 */
class CryptingTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('crypting');
		$suite->addTestDirectory(dirname(__FILE__) . '/crypting/');
		return $suite;
	}
}