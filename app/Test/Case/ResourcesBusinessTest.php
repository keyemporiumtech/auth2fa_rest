<?php

/*
 * Suite di test per il business del modulo resources
 */
class ResourcesBusinessTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('resources->Business');
		$suite->addTestDirectory(dirname(__FILE__) . '/resources/business/');
		return $suite;
	}
}
