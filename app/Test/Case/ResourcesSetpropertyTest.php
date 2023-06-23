<?php

/*
 * Suite di test per le utility del modulo resources 
 */
class ResourcesUtilityTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('resources->SetProperties');
		$suite->addTestDirectory(dirname(__FILE__) . '/resources/setproperty/');
		return $suite;
	}
}