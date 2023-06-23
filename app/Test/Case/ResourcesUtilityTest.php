<?php

/*
 * Suite di test per le utility del modulo resources 
 */
class ResourcesUtilityTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('resources->Utility');
		$suite->addTestDirectory(dirname(__FILE__) . '/resources/utility/');
		return $suite;
	}
}