<?php

/*
 * Suite di test per il delegate del modulo work_cv
 */
class WorkcvDelegateTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('work_cv->Delegate');
		$suite->addTestDirectory(dirname(__FILE__) . '/work_cv/delegate/');
		return $suite;
	}
}
