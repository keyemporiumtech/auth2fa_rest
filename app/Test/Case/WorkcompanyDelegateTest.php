<?php

/*
 * Suite di test per il delegate del modulo work_company
 */
class WorkcompanyDelegateTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('work_company->Delegate');
		$suite->addTestDirectory(dirname(__FILE__) . '/work_company/delegate/');
		return $suite;
	}
}
