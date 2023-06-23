<?php
App::uses("StringUtility", "modules/coreutils/utility");

class StringContainsTest extends CakeTestCase {
	public $array;

	public function setUp() {
		parent::setUp();
		$this->array= array (
				"ia",
				"xxx" 
		);
	}

	function testContains() {
		$result1= StringUtility::contains("ciao", "ia");
		$this->assertEquals($result1, true);
	}

	function testContainsAll() {
		$result1= StringUtility::containsAll("ciao", $this->array);
		$this->assertEquals($result1, true);
	}

	function testContainsArrayByString() {
		$result1= StringUtility::containsArrayByString("ciao,mondo,buono", "ia");
		$result2= StringUtility::containsArrayByString("ciao,mondo,buono", "mondo");
		$this->assertEquals($result1, false);
		$this->assertEquals($result2, true);
	}

	function testStringInArray() {
		$result1= StringUtility::stringInArray("ciao", $this->array);
		$result2= StringUtility::stringInArray("ia", $this->array);
		$result3= StringUtility::stringInArray("mondo", $this->array);
		$this->assertEquals($result1, true);
		$this->assertEquals($result2, true);
		$this->assertEquals($result3, false);
	}
}
