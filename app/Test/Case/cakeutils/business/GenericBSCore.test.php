<?php
App::uses("TestfkBS", "modules/cakeutils/business");

class GenericBSCoreTest extends CakeTestCase {

	function testAddPropertyDao() {
		$bs= new TestfkBS();
		$bs->addPropertyDao("prop1", "val1");
		$this->assertEquals($bs->dao->prop1, "val1");
	}

	function testInstance() {
		$bs= new TestfkBS();
		$obj= $bs->instance();
		$this->assertEquals(array_key_exists("cod", $obj ['Testfk']), true);
	}
}