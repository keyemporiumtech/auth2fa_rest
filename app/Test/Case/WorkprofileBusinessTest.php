<?php

/*
 * Suite di test per il business del modulo work_profile
 */
class WorkprofileBusinessTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('work_profile->Business');
		$suite->addTestDirectory(dirname(__FILE__) . '/work_profile/business/');
		return $suite;
	}
}
