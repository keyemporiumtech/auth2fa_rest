<?php
App::uses("DateUtility", "modules/coreutils/utility");

class DateCompareTest extends CakeTestCase {

	public function setUp() {
		parent::setUp();
	}

	function testAddToDate() {
		$result1= DateUtility::addToDate("2020-09-15 11:12:30", 2, "+", "d", "Y-m-d H:i:s");
		$result2= DateUtility::addToDate("2020-09-15 11:12:30", 2, "+", "H", "Y-m-d H:i:s");
		$result3= DateUtility::addToDate("2020-09-15 11:12:30", 2, "+", "d");
		$this->assertEquals($result1, "2020-09-17 11:12:30");
		$this->assertEquals($result2, "2020-09-15 13:12:30");
		$this->assertEquals($result3, 1600333950);
	}

	function testDiffDate() {
		$result1= DateUtility::diffDate("2020-09-15 11:12:30", "2020-09-17 13:12:30", "d");
		$result2= DateUtility::diffDate("2020-09-15 11:12:30", "2020-09-15 14:15:30", "H");
		$this->assertEquals($result1, 2);
		$this->assertEquals($result2, 3);
	}

	function testEndMax() {
		$result1= DateUtility::endMax("2020-09-15 11:12:30", "2020-09-17 13:12:30");
		$result2= DateUtility::endMax("2020-09-15 11:12:30", "2020-09-15 10:15:30");
		$this->assertEquals($result1, true);
		$this->assertEquals($result2, false);
	}

	function testBeetwenDates() {
		$result1= DateUtility::beetwenDates("2020-09-16 11:12:30", "2020-09-15 11:12:30", "2020-09-17 13:12:30");
		$result2= DateUtility::beetwenDates("2020-09-14 11:12:30", "2020-09-15 11:12:30", "2020-09-15 10:15:30");
		$this->assertEquals($result1, true);
		$this->assertEquals($result2, false);
	}
}
