<?php
App::uses("CryptingUtility", "modules/crypting/utility");

class CryptingUtilityTest extends CakeTestCase {
	public $list;

	public function setUp() {
		parent::setUp();
	}

	function testEvaluateAvoidEncrypt() {
		$result1= CryptingUtility::evaluateAvoidEncrypt("i/ciao=T");
		$this->assertEquals($result1, "i*ciao[T");
	}

	function testEvaluateAvoidDecrypt() {
		$result1= CryptingUtility::evaluateAvoidDecrypt("i*ciao[T");
		$this->assertEquals($result1, "i/ciao=T");
	}
}
