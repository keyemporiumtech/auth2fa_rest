<?php

/*
 * Suite di test per il modulo work_cv
 */
class WorkcvTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('work_cv');
		$suite->addTestDirectory(dirname(__FILE__) . '/work_cv/');
		$suite->addTestDirectory(dirname(__FILE__) . '/work_cv/business/');
		$suite->addTestDirectory(dirname(__FILE__) . '/work_cv/delegate/');
		$suite->addTestDirectory(dirname(__FILE__) . '/work_cv/controller/');
		// $suite->addTestDirectory(dirname(__FILE__) . '/work_cv/utility/');		
		return $suite;
	}
}
