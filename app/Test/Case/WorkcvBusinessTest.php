<?php

/*
 * Suite di test per il business del modulo work_cv
 */
class WorkcvBusinessTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('work_cv->Business');
		$suite->addTestDirectory(dirname(__FILE__) . '/work_cv/business/');
		return $suite;
	}
}
