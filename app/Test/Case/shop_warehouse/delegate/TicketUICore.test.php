<?php
App::uses("TicketUI", "modules/shop_warehouse/delegate");
App::uses("TicketBS", "modules/shop_warehouse/business");
App::uses("MysqlUtilityTest", "Test/utility");

class TicketUICoreTest extends CakeTestCase {

	function addRecord() {
		$bs= new TicketBS();
		$num= $bs->count();
		if ($num == 0) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "tickets");
			$bs= new TicketBS();
			$obj= $bs->instance();
			$obj ['Ticket'] ['cod']= "mioCodTest";		
			$id= $bs->save($obj);
			return $autoIncrement;
		}
		return null;
	}

	function removeRecord($autoIncrement) {
		if (! empty($autoIncrement)) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			MysqlUtilityTest::deleteLast($dbo, "tickets", "cod='mioCodTest'");
			MysqlUtilityTest::resetAutoIncrement($dbo, $this, "tickets", $autoIncrement);
			MysqlUtilityTest::verifyDeleted($dbo, $this, "tickets", "cod='mioCodTest'");
		}
	}

	function testGet() {
		$autoIncrement= $this->addRecord();
		$ui= new TicketUI();
		$obj= $ui->get(1);
		$this->assertEquals($obj ['Ticket'] ['id'], 1);
		$this->removeRecord($autoIncrement);
	}

	function testTable() {
		$autoIncrement= $this->addRecord();
		$condition= new DBCondition();
		$condition->key= "id";
		$condition->value= 1;
		$conditions= array (
				$condition
		);
		$ui= new TicketUI();
		$paginator= $ui->table($conditions);
		$this->assertEquals($paginator ['list'] [0] ['Ticket'] ['id'], 1);
		$this->removeRecord($autoIncrement);
	}

	function testSave() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "tickets");
		
		// obj
		$bs= new TicketBS();
		$obj= $bs->instance();
		$obj ['Ticket'] ['cod']= "mioCodTest";
		
		// save
		$ui= new TicketUI();
		$id= $ui->save($obj);
		
		// search
		$search= "SELECT * FROM tickets WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['tickets'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');
		
		// delete
		MysqlUtilityTest::deleteLast($dbo, "tickets", "cod='mioCodTest'");
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "tickets", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "tickets", "cod='mioCodTest'");
	}

	function testEdit() {
		$autoIncrement= $this->addRecord();
		
		// obj
		$id= 1;
		$bs= new TicketBS();
		$obj= $bs->unique($id);
		
		$objNew= $obj;
		$objNew ['Ticket'] ['cod']= "OthermioCodTest";
		
		// edit
		$ui= new TicketUI();
		$id= $ui->edit($id, $objNew);
		
		// test
		$bs= new TicketBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Ticket'] ['cod'], 'OthermioCodTest');
		
		// reset
		$ui= new TicketUI();
		$id= $ui->edit($id, $obj);
		
		// test
		$bs= new TicketBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Ticket'] ['cod'] == 'OthermioCodTest', false);
		$this->removeRecord($autoIncrement);
	}

	function testDelete() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "tickets");
		
		// insert
		$sql= "INSERT INTO tickets (id,cod,created) VALUES";
		$sql.= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
		$data= $dbo->query($sql);
		
		// search
		$search= "SELECT * FROM tickets WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['tickets'];
		$id= $result ['id'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');		
		
		// delete
		$ui= new TicketUI();
		$ui->delete($id);
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "tickets", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "tickets", "cod='mioCodTest'");
	}
}