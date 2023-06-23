<?php
App::uses("Test", "Model");

class EntityConnectionTest extends CakeTestCase {

	function testSelect() {
		$tests= new Test();
		$list= $tests->find('all');
		$this->assertEquals(count($list) > 0, true);
		$this->assertEquals($list [0] ['Test'] ["id"], 1);
	}
}