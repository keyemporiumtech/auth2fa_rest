<?php

/*
 * Suite di test per il controller del modulo work_cv
 */
class WorkcvControllerTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('work_cv->Controller');
		$suite->addTestDirectory(dirname(__FILE__) . '/work_cv/controller/');
		return $suite;
	}
}
