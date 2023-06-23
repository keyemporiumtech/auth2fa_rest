<?php

/*
 * Suite di test per il business del modulo calendar
 */
class CalendarBusinessTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('calendar->Business');
		$suite->addTestDirectory(dirname(__FILE__) . '/calendar/business/');
		return $suite;
	}
}
