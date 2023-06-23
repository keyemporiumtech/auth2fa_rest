<?php
App::uses("TicketattachmentBS", "modules/shop_warehouse/business");
App::uses("MysqlUtilityTest", "Test/utility");

class TicketattachmentBSCoreTest extends CakeTestCase {

	function addRecord() {
		$bs= new TicketattachmentBS();
		$num= $bs->count();
		if ($num == 0) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "ticketattachments");
			$bs= new TicketattachmentBS();
			$obj= $bs->instance();
			$obj ['Ticketattachment'] ['cod']= "mioCodTest";		
			$id= $bs->save($obj);
			return $autoIncrement;
		}
		return null;
	}

	function removeRecord($autoIncrement) {
		if (! empty($autoIncrement)) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			MysqlUtilityTest::deleteLast($dbo, "ticketattachments", "cod='mioCodTest'");
			MysqlUtilityTest::resetAutoIncrement($dbo, $this, "ticketattachments", $autoIncrement);
			MysqlUtilityTest::verifyDeleted($dbo, $this, "ticketattachments", "cod='mioCodTest'");
		}
	}

	function testUnique() {
		$autoIncrement= $this->addRecord();
		$bs= new TicketattachmentBS();
		$obj= $bs->unique(1);
		$this->assertEquals($obj ['Ticketattachment'] ['id'], 1);
		$this->removeRecord($autoIncrement);
	}

	function testAll() {
		$autoIncrement= $this->addRecord();
		$bs= new TicketattachmentBS();
		$bs->addCondition("id", 1);
		$list= $bs->all();
		$this->assertEquals($list [0] ['Ticketattachment'] ['id'], 1);
		$this->removeRecord($autoIncrement);
	}

	function testSave() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "ticketattachments");
		
		// obj
		$bs= new TicketattachmentBS();
		$obj= $bs->instance();
		$obj ['Ticketattachment'] ['cod']= "mioCodTest";		
		
		// save
		$bs= new TicketattachmentBS();
		$id= $bs->save($obj);
		
		// search
		$search= "SELECT * FROM ticketattachments WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['ticketattachments'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');		
		
		// delete
		MysqlUtilityTest::deleteLast($dbo, "ticketattachments", "cod='mioCodTest'");
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "ticketattachments", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "ticketattachments", "cod='mioCodTest'");
	}

	function testEdit() {
		$autoIncrement= $this->addRecord();
		
		// obj
		$id= 1;
		$bs= new TicketattachmentBS();
		$obj= $bs->unique($id);
		
		$objNew= $obj;
		$objNew ['Ticketattachment'] ['cod']= "OthermioCodTest";
		
		// edit
		$bs= new TicketattachmentBS();
		$id= $bs->save($objNew);
		
		// test
		$bs= new TicketattachmentBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Ticketattachment'] ['cod'], 'OthermioCodTest');
		
		// reset
		$bs= new TicketattachmentBS();
		$id= $bs->save($obj);
		
		// test
		$bs= new TicketattachmentBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Ticketattachment'] ['cod'] == 'OthermioCodTest', false);
		$this->removeRecord($autoIncrement);
	}

	function testDelete() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "ticketattachments");
		
		// insert
		$sql= "INSERT INTO ticketattachments (id,cod,created) VALUES";
		$sql.= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
		$data= $dbo->query($sql);
		
		// search
		$search= "SELECT * FROM ticketattachments WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['ticketattachments'];
		$id= $result ['id'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');		
		
		// delete
		$bs= new TicketattachmentBS();
		$id= $bs->delete($id);
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "ticketattachments", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "ticketattachments", "cod='mioCodTest'");
	}
}