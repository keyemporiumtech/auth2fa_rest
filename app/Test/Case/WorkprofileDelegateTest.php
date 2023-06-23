<?php

/*
 * Suite di test per il delegate del modulo work_profile
 */
class WorkprofileDelegateTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('work_profile->Delegate');
		$suite->addTestDirectory(dirname(__FILE__) . '/work_profile/delegate/');
		return $suite;
	}
}
