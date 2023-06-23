<?php

/*
 * Suite di test per il controller del modulo resources
 */
class ResourcesControllerTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('resources->Controller');
		$suite->addTestDirectory(dirname(__FILE__) . '/resources/controller/');
		return $suite;
	}
}
