<?php
App::uses("DateUtility", "modules/coreutils/utility");

class DateOperationsTest extends CakeTestCase {

	public function setUp() {
		parent::setUp();
	}

	function testGetDateByTimestamp() {
		$result1= DateUtility::getDateByTimestamp("2020-09-15 11:12:30");
		$this->assertEquals($result1, "15/09/2020");
	}

	function testGetTimestampByDate() {
		$result1= DateUtility::getTimestampByDate("15/09/2020 11:12:30");
		$this->assertEquals($result1, "2020-09-15");
	}

	function testGetDateByTimestampHH() {
		$result1= DateUtility::getDateByTimestampHH("2020-09-15 11:12:30");
		$this->assertEquals($result1, "15/09/2020 11:12");
	}

	function testGetTimestampByDateHH() {
		$result1= DateUtility::getTimestampByDateHH("15/09/2020 11:12:30");
		$this->assertEquals($result1, "2020-09-15 11:12");
	}

	function testGetDateCalendar() {
		$result1= DateUtility::getDateCalendar("2020-09-15 11:12:30");
		$this->assertEquals($result1, "20200915");
	}

	function testGetTimeCalendar() {
		$result1= DateUtility::getTimeCalendar("2020-09-15 11:12:30");
		$this->assertEquals($result1, "11.12");
	}

	function testGetDateFormat() {
		$result1= DateUtility::getDateFormat("d/m/Y H:i", "2020-09-15 11:12:30");
		$this->assertEquals($result1, "15/09/2020 11:12");
	}

	function testIsEmptyTimestamp() {
		$result1= DateUtility::isEmptyTimestamp("2020-09-15 11:12:30");
		$result2= DateUtility::isEmptyTimestamp("1970-01-01 11:12:30");
		$result3= DateUtility::isEmptyTimestamp("");
		$this->assertEquals($result1, false);
		$this->assertEquals($result2, true);
		$this->assertEquals($result3, true);
	}

	function testIsTimestamp() {
		$result1= DateUtility::isTimestamp("2020-09-15 11:12:30");
		$result2= DateUtility::isTimestamp("1970-01-01 11:12:30");
		$result3= DateUtility::isTimestamp("15/09/2020 11:12:30");
		$this->assertEquals($result1, true);
		$this->assertEquals($result2, true);
		$this->assertEquals($result3, false);
	}

	function testGetNumggMese() {
		$result1= DateUtility::getNumggMese(9, 2020);
		$result2= DateUtility::getNumggMese(10, 2020);
		$result3= DateUtility::getNumggMese(2, 2020);
		$result4= DateUtility::getNumggMese(2, 2019);
		$this->assertEquals($result1, 30);
		$this->assertEquals($result2, 31);
		$this->assertEquals($result3, 29);
		$this->assertEquals($result4, 28);
	}

	function testGetAnnoBisestile() {
		$result1= DateUtility::getAnnoBisestile(2020);
		$result2= DateUtility::getAnnoBisestile(2019);
		$this->assertEquals($result1, true);
		$this->assertEquals($result2, false);
	}
}
