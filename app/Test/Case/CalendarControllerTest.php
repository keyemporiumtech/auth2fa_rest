<?php

/*
 * Suite di test per il controller del modulo calendar
 */
class CalendarControllerTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('calendar->Controller');
		$suite->addTestDirectory(dirname(__FILE__) . '/calendar/controller/');
		return $suite;
	}
}
