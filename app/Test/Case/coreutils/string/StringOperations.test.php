<?php
App::uses("StringUtility", "modules/coreutils/utility");

class StringOperationsTest extends CakeTestCase {
	public $arrayParams;

	public function setUp() {
		parent::setUp();
	}

	function testGetFillZeroByString() {
		$result1= StringUtility::getFillZeroByString("ciao", 3);
		$this->assertEquals($result1, "000ciao");
	}

	function testGetFillByString() {
		$result1= StringUtility::getFillByString("ciao", 3, "3");
		$this->assertEquals($result1, "333ciao");
	}

	function testShortString() {
		$result1= StringUtility::shortString("ciao", 3);
		$this->assertEquals($result1, "cia...");
	}

	function testCleanFromCharToEnd() {
		$result1= StringUtility::cleanFromCharToEnd("ciaomondo", "ao");
		$result2= StringUtility::cleanFromCharToEnd("ciaomondo", "ao", true);
		$this->assertEquals($result1, "ci");
		$this->assertEquals($result2, "ciao");
	}

	function testCleanFromInitToWord() {
		$result1= StringUtility::cleanFromInitToWord("ciaomondo", "ao");
		$result2= StringUtility::cleanFromInitToWord("ciaomondo", "ao", true);
		$this->assertEquals($result1, "aomondo");
		$this->assertEquals($result2, "mondo");
	}

	function testTrim() {
		$result1= StringUtility::trim("ciao mondo ");
		$this->assertEquals($result1, "ciaomondo");
	}
}
