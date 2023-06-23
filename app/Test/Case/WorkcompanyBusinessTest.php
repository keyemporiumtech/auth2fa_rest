<?php

/*
 * Suite di test per il business del modulo work_company
 */
class WorkcompanyBusinessTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('work_company->Business');
		$suite->addTestDirectory(dirname(__FILE__) . '/work_company/business/');
		return $suite;
	}
}
