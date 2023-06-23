<?php

/*
 * Suite di test per il modulo resources
 */
class ResourcesTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('resources');
		$suite->addTestDirectory(dirname(__FILE__) . '/resources/');
		$suite->addTestDirectory(dirname(__FILE__) . '/resources/business/');
		$suite->addTestDirectory(dirname(__FILE__) . '/resources/delegate/');
		$suite->addTestDirectory(dirname(__FILE__) . '/resources/controller/');
		$suite->addTestDirectory(dirname(__FILE__) . '/resources/utility/');
		$suite->addTestDirectory(dirname(__FILE__) . '/resources/setproperty/');
		return $suite;
	}
}
