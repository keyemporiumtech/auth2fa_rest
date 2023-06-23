<?php

/*
 * Suite di test per il delegate del modulo calendar
 */
class CalendarDelegateTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('calendar->Delegate');
		$suite->addTestDirectory(dirname(__FILE__) . '/calendar/delegate/');
		return $suite;
	}
}
