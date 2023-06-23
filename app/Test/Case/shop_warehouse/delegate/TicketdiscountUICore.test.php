<?php
App::uses("TicketdiscountUI", "modules/shop_warehouse/delegate");
App::uses("TicketdiscountBS", "modules/shop_warehouse/business");
App::uses("MysqlUtilityTest", "Test/utility");

class TicketdiscountUICoreTest extends CakeTestCase {

	function addRecord() {
		$bs= new TicketdiscountBS();
		$num= $bs->count();
		if ($num == 0) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "ticketdiscounts");
			$bs= new TicketdiscountBS();
			$obj= $bs->instance();
			$obj ['Ticketdiscount'] ['cod']= "mioCodTest";		
			$id= $bs->save($obj);
			return $autoIncrement;
		}
		return null;
	}

	function removeRecord($autoIncrement) {
		if (! empty($autoIncrement)) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			MysqlUtilityTest::deleteLast($dbo, "ticketdiscounts", "cod='mioCodTest'");
			MysqlUtilityTest::resetAutoIncrement($dbo, $this, "ticketdiscounts", $autoIncrement);
			MysqlUtilityTest::verifyDeleted($dbo, $this, "ticketdiscounts", "cod='mioCodTest'");
		}
	}

	function testGet() {
		$autoIncrement= $this->addRecord();
		$ui= new TicketdiscountUI();
		$obj= $ui->get(1);
		$this->assertEquals($obj ['Ticketdiscount'] ['id'], 1);
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
		$ui= new TicketdiscountUI();
		$paginator= $ui->table($conditions);
		$this->assertEquals($paginator ['list'] [0] ['Ticketdiscount'] ['id'], 1);
		$this->removeRecord($autoIncrement);
	}

	function testSave() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "ticketdiscounts");
		
		// obj
		$bs= new TicketdiscountBS();
		$obj= $bs->instance();
		$obj ['Ticketdiscount'] ['cod']= "mioCodTest";
		
		// save
		$ui= new TicketdiscountUI();
		$id= $ui->save($obj);
		
		// search
		$search= "SELECT * FROM ticketdiscounts WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['ticketdiscounts'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');
		
		// delete
		MysqlUtilityTest::deleteLast($dbo, "ticketdiscounts", "cod='mioCodTest'");
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "ticketdiscounts", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "ticketdiscounts", "cod='mioCodTest'");
	}

	function testEdit() {
		$autoIncrement= $this->addRecord();
		
		// obj
		$id= 1;
		$bs= new TicketdiscountBS();
		$obj= $bs->unique($id);
		
		$objNew= $obj;
		$objNew ['Ticketdiscount'] ['cod']= "OthermioCodTest";
		
		// edit
		$ui= new TicketdiscountUI();
		$id= $ui->edit($id, $objNew);
		
		// test
		$bs= new TicketdiscountBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Ticketdiscount'] ['cod'], 'OthermioCodTest');
		
		// reset
		$ui= new TicketdiscountUI();
		$id= $ui->edit($id, $obj);
		
		// test
		$bs= new TicketdiscountBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Ticketdiscount'] ['cod'] == 'OthermioCodTest', false);
		$this->removeRecord($autoIncrement);
	}

	function testDelete() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "ticketdiscounts");
		
		// insert
		$sql= "INSERT INTO ticketdiscounts (id,cod,created) VALUES";
		$sql.= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
		$data= $dbo->query($sql);
		
		// search
		$search= "SELECT * FROM ticketdiscounts WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['ticketdiscounts'];
		$id= $result ['id'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');		
		
		// delete
		$ui= new TicketdiscountUI();
		$ui->delete($id);
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "ticketdiscounts", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "ticketdiscounts", "cod='mioCodTest'");
	}
}