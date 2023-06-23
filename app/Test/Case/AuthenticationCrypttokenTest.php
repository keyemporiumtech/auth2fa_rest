<?php

/*
 * Suite di test per la gestione del token del modulo authentication 
 */
class AuthenticationCrypttokenTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('authentication->CryptToken');
		$suite->addTestDirectory(dirname(__FILE__) . '/authentication/crypttoken/');
		return $suite;
	}
}