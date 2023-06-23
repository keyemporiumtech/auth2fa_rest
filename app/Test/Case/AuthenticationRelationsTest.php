<?php

/*
 * Suite di test per la gestione delle entità di relazione del modulo authentication 
 */
class AuthenticationRelationsTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('authentication->Relations');
		$suite->addTestDirectory(dirname(__FILE__) . '/authentication/relations/');
		return $suite;
	}
}