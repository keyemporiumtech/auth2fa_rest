<?php

/*
 * Suite di test per il modulo work_company
 */
class WorkcompanyTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('work_company');
		$suite->addTestDirectory(dirname(__FILE__) . '/work_company/');
		$suite->addTestDirectory(dirname(__FILE__) . '/work_company/business/');
		$suite->addTestDirectory(dirname(__FILE__) . '/work_company/delegate/');
		$suite->addTestDirectory(dirname(__FILE__) . '/work_company/controller/');
		// $suite->addTestDirectory(dirname(__FILE__) . '/work_company/utility/');		
		return $suite;
	}
}
