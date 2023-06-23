<?php
App::uses("TicketBS", "modules/shop_warehouse/business");
App::uses("MysqlUtilityTest", "Test/utility");

class TicketBSCoreTest extends CakeTestCase {

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

	function testUnique() {
		$autoIncrement= $this->addRecord();
		$bs= new TicketBS();
		$obj= $bs->unique(1);
		$this->assertEquals($obj ['Ticket'] ['id'], 1);
		$this->removeRecord($autoIncrement);
	}

	function testAll() {
		$autoIncrement= $this->addRecord();
		$bs= new TicketBS();
		$bs->addCondition("id", 1);
		$list= $bs->all();
		$this->assertEquals($list [0] ['Ticket'] ['id'], 1);
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
		$bs= new TicketBS();
		$id= $bs->save($obj);
		
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
		$bs= new TicketBS();
		$id= $bs->save($objNew);
		
		// test
		$bs= new TicketBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Ticket'] ['cod'], 'OthermioCodTest');
		
		// reset
		$bs= new TicketBS();
		$id= $bs->save($obj);
		
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
		$bs= new TicketBS();
		$id= $bs->delete($id);
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "tickets", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "tickets", "cod='mioCodTest'");
	}
}