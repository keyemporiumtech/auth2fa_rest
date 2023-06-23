<?php
App::uses("EventattachmentUI", "modules/calendar/delegate");
App::uses("EventattachmentBS", "modules/calendar/business");
App::uses("MysqlUtilityTest", "Test/utility");

class EventattachmentUICoreTest extends CakeTestCase {

	function addRecord() {
		$bs= new EventattachmentBS();
		$num= $bs->count();
		if ($num == 0) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "eventattachments");
			$bs= new EventattachmentBS();
			$obj= $bs->instance();
			$obj ['Eventattachment'] ['cod']= "mioCodTest";		
			$id= $bs->save($obj);
			return $autoIncrement;
		}
		return null;
	}

	function removeRecord($autoIncrement) {
		if (! empty($autoIncrement)) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			MysqlUtilityTest::deleteLast($dbo, "eventattachments", "cod='mioCodTest'");
			MysqlUtilityTest::resetAutoIncrement($dbo, $this, "eventattachments", $autoIncrement);
			MysqlUtilityTest::verifyDeleted($dbo, $this, "eventattachments", "cod='mioCodTest'");
		}
	}

	function testGet() {
		$autoIncrement= $this->addRecord();
		$ui= new EventattachmentUI();
		$obj= $ui->get(1);
		$this->assertEquals($obj ['Eventattachment'] ['id'], 1);
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
		$ui= new EventattachmentUI();
		$paginator= $ui->table($conditions);
		$this->assertEquals($paginator ['list'] [0] ['Eventattachment'] ['id'], 1);
		$this->removeRecord($autoIncrement);
	}

	function testSave() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "eventattachments");
		
		// obj
		$bs= new EventattachmentBS();
		$obj= $bs->instance();
		$obj ['Eventattachment'] ['cod']= "mioCodTest";
		
		// save
		$ui= new EventattachmentUI();
		$id= $ui->save($obj);
		
		// search
		$search= "SELECT * FROM eventattachments WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['eventattachments'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');
		
		// delete
		MysqlUtilityTest::deleteLast($dbo, "eventattachments", "cod='mioCodTest'");
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "eventattachments", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "eventattachments", "cod='mioCodTest'");
	}

	function testEdit() {
		$autoIncrement= $this->addRecord();
		
		// obj
		$id= 1;
		$bs= new EventattachmentBS();
		$obj= $bs->unique($id);
		
		$objNew= $obj;
		$objNew ['Eventattachment'] ['cod']= "OthermioCodTest";
		
		// edit
		$ui= new EventattachmentUI();
		$id= $ui->edit($id, $objNew);
		
		// test
		$bs= new EventattachmentBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Eventattachment'] ['cod'], 'OthermioCodTest');
		
		// reset
		$ui= new EventattachmentUI();
		$id= $ui->edit($id, $obj);
		
		// test
		$bs= new EventattachmentBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Eventattachment'] ['cod'] == 'OthermioCodTest', false);
		$this->removeRecord($autoIncrement);
	}

	function testDelete() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "eventattachments");
		
		// insert
		$sql= "INSERT INTO eventattachments (id,cod,created) VALUES";
		$sql.= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
		$data= $dbo->query($sql);
		
		// search
		$search= "SELECT * FROM eventattachments WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['eventattachments'];
		$id= $result ['id'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');		
		
		// delete
		$ui= new EventattachmentUI();
		$ui->delete($id);
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "eventattachments", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "eventattachments", "cod='mioCodTest'");
	}
}