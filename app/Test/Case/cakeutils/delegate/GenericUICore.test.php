<?php
App::uses("TestfkUI", "modules/cakeutils/delegate");

class GenericUICoreTest extends CakeTestCase {

	function testGet() {
		$ui= new TestfkUI();
		$obj= $ui->get(1);
		$this->assertEquals($obj ['Testfk'] ['id'], 1);
	}

	function testTable() {
		$ui= new TestfkUI();
		$paginator= $ui->table();
		$this->assertEquals($paginator ['list'] [0] ['Testfk'] ['id'], 1);
		$this->assertEquals($paginator ['count'], 0);
		$this->assertEquals($paginator ['pages'], 0);
	}

	function testTablePaginate() {
		$ui= new TestfkUI();
		$paginate= new DBPaginate();
		$paginate->limit= 1;
		$paginate->page= 2;
		$paginator= $ui->table(null, null, $paginate);
		$this->assertEquals($paginator ['list'] [0] ['Testfk'] ['id'], 2);
		$this->assertEquals(count($paginator ['list']), 1);
		$this->assertEquals($paginator ['count'], 2);
		$this->assertEquals($paginator ['pages'], 2);
	}
}