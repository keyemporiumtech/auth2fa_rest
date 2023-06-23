<?php
App::uses("TicketreservesettingUI", "modules/shop_warehouse/delegate");
App::uses("TicketreservesettingBS", "modules/shop_warehouse/business");
App::uses("MysqlUtilityTest", "Test/utility");

class TicketreservesettingUICoreTest extends CakeTestCase {

	function addRecord() {
		$bs= new TicketreservesettingBS();
		$num= $bs->count();
		if ($num == 0) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "ticketreservesettings");
			$bs= new TicketreservesettingBS();
			$obj= $bs->instance();
			$obj ['Ticketreservesetting'] ['cod']= "mioCodTest";		
			$id= $bs->save($obj);
			return $autoIncrement;
		}
		return null;
	}

	function removeRecord($autoIncrement) {
		if (! empty($autoIncrement)) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			MysqlUtilityTest::deleteLast($dbo, "ticketreservesettings", "cod='mioCodTest'");
			MysqlUtilityTest::resetAutoIncrement($dbo, $this, "ticketreservesettings", $autoIncrement);
			MysqlUtilityTest::verifyDeleted($dbo, $this, "ticketreservesettings", "cod='mioCodTest'");
		}
	}

	function testGet() {
		$autoIncrement= $this->addRecord();
		$ui= new TicketreservesettingUI();
		$obj= $ui->get(1);
		$this->assertEquals($obj ['Ticketreservesetting'] ['id'], 1);
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
		$ui= new TicketreservesettingUI();
		$paginator= $ui->table($conditions);
		$this->assertEquals($paginator ['list'] [0] ['Ticketreservesetting'] ['id'], 1);
		$this->removeRecord($autoIncrement);
	}

	function testSave() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "ticketreservesettings");
		
		// obj
		$bs= new TicketreservesettingBS();
		$obj= $bs->instance();
		$obj ['Ticketreservesetting'] ['cod']= "mioCodTest";
		
		// save
		$ui= new TicketreservesettingUI();
		$id= $ui->save($obj);
		
		// search
		$search= "SELECT * FROM ticketreservesettings WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['ticketreservesettings'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');
		
		// delete
		MysqlUtilityTest::deleteLast($dbo, "ticketreservesettings", "cod='mioCodTest'");
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "ticketreservesettings", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "ticketreservesettings", "cod='mioCodTest'");
	}

	function testEdit() {
		$autoIncrement= $this->addRecord();
		
		// obj
		$id= 1;
		$bs= new TicketreservesettingBS();
		$obj= $bs->unique($id);
		
		$objNew= $obj;
		$objNew ['Ticketreservesetting'] ['cod']= "OthermioCodTest";
		
		// edit
		$ui= new TicketreservesettingUI();
		$id= $ui->edit($id, $objNew);
		
		// test
		$bs= new TicketreservesettingBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Ticketreservesetting'] ['cod'], 'OthermioCodTest');
		
		// reset
		$ui= new TicketreservesettingUI();
		$id= $ui->edit($id, $obj);
		
		// test
		$bs= new TicketreservesettingBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Ticketreservesetting'] ['cod'] == 'OthermioCodTest', false);
		$this->removeRecord($autoIncrement);
	}

	function testDelete() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "ticketreservesettings");
		
		// insert
		$sql= "INSERT INTO ticketreservesettings (id,cod,created) VALUES";
		$sql.= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
		$data= $dbo->query($sql);
		
		// search
		$search= "SELECT * FROM ticketreservesettings WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['ticketreservesettings'];
		$id= $result ['id'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');		
		
		// delete
		$ui= new TicketreservesettingUI();
		$ui->delete($id);
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "ticketreservesettings", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "ticketreservesettings", "cod='mioCodTest'");
	}
}