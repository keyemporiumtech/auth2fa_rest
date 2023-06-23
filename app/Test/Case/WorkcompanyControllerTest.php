<?php

/*
 * Suite di test per il controller del modulo work_company
 */
class WorkcompanyControllerTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('work_company->Controller');
		$suite->addTestDirectory(dirname(__FILE__) . '/work_company/controller/');
		return $suite;
	}
}
