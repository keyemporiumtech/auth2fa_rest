<?php

/*
 * Suite di test per il controller del modulo work_profile
 */
class WorkprofileControllerTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('work_profile->Controller');
		$suite->addTestDirectory(dirname(__FILE__) . '/work_profile/controller/');
		return $suite;
	}
}
