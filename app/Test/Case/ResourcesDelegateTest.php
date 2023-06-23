<?php

/*
 * Suite di test per il delegate del modulo resources
 */
class ResourcesDelegateTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('resources->Delegate');
		$suite->addTestDirectory(dirname(__FILE__) . '/resources/delegate/');
		return $suite;
	}
}
