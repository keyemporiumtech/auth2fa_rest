<?php
App::uses("ConnectionManager", "Model");
App::uses("TestfkBS", "modules/cakeutils/business");

class GenericBSQueryTest extends CakeTestCase {

	function testAll() {
		$bs= new TestfkBS();
		$list= $bs->all();
		$this->assertEquals($list [0] ['Testfk'] ['id'], 1);
	}

	function testUnique() {
		$bs= new TestfkBS();
		$obj= $bs->unique(1);
		$this->assertEquals($obj ['Testfk'] ['id'], 1);
		
		$bs= new TestfkBS();
		$bs->addCondition("cod", "FK002");
		$obj= $bs->unique();
		$this->assertEquals($obj ['Testfk'] ['id'], 2);
	}

	function testCount() {
		$bs= new TestfkBS();
		$this->assertEquals($bs->count(), 2);
	}

	function testGetCountForPaginate() {
		$bs= new TestfkBS();
		$bs->setPaginate(1);
		$this->assertEquals($bs->getCountForPaginate(), 2);
	}

	function testQueryCount() {
		$bs= new TestfkBS();
		$sql= "SELECT COUNT(*) as num FROM testfks";
		$this->assertEquals($bs->queryCount($sql, "num"), 2);
	}

	function testQuery() {
		$bs= new TestfkBS();
		$sql= "SELECT * FROM testfks as Testfk WHERE id=1";
		$obj= $bs->query($sql);
		$this->assertEquals($obj ['Testfk'] ['id'], 1);
		
		$bs= new TestfkBS();
		$obj= $bs->query($sql, false);
		$this->assertEquals($obj [0] ['Testfk'] ['id'], 1);
	}
}