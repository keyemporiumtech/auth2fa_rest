<?php

/*
 * Suite di test per il modulo work_profile
 */
class WorkprofileTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('work_profile');
		$suite->addTestDirectory(dirname(__FILE__) . '/work_profile/');
		$suite->addTestDirectory(dirname(__FILE__) . '/work_profile/business/');
		$suite->addTestDirectory(dirname(__FILE__) . '/work_profile/delegate/');
		$suite->addTestDirectory(dirname(__FILE__) . '/work_profile/controller/');
		// $suite->addTestDirectory(dirname(__FILE__) . '/work_profile/utility/');		
		return $suite;
	}
}
