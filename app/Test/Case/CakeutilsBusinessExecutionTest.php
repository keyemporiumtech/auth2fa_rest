<?php

/*
 * Suite di test per il business del modulo cakeutils con operazioni di modifica del db 
 */
class CakeutilsBusinessExecutionTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('cakeutils->BusinessExecution');
		$suite->addTestDirectory(dirname(__FILE__) . '/cakeutils/businessexecution/');
		return $suite;
	}
}