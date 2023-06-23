<?php

/*
 * Suite di test per il modulo calendar
 */
class CalendarTest extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite= new CakeTestSuite('calendar');
		$suite->addTestDirectory(dirname(__FILE__) . '/calendar/');
		$suite->addTestDirectory(dirname(__FILE__) . '/calendar/business/');
		$suite->addTestDirectory(dirname(__FILE__) . '/calendar/delegate/');
		$suite->addTestDirectory(dirname(__FILE__) . '/calendar/controller/');
		return $suite;
	}
}
