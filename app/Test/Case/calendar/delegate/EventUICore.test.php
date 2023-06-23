<?php
App::uses("EventUI", "modules/calendar/delegate");
App::uses("EventBS", "modules/calendar/business");
App::uses("MysqlUtilityTest", "Test/utility");

class EventUICoreTest extends CakeTestCase {

	function addRecord() {
		$bs= new EventBS();
		$num= $bs->count();
		if ($num == 0) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "events");
			$bs= new EventBS();
			$obj= $bs->instance();
			$obj ['Event'] ['cod']= "mioCodTest";		
			$id= $bs->save($obj);
			return $autoIncrement;
		}
		return null;
	}

	function removeRecord($autoIncrement) {
		if (! empty($autoIncrement)) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			MysqlUtilityTest::deleteLast($dbo, "events", "cod='mioCodTest'");
			MysqlUtilityTest::resetAutoIncrement($dbo, $this, "events", $autoIncrement);
			MysqlUtilityTest::verifyDeleted($dbo, $this, "events", "cod='mioCodTest'");
		}
	}

	function testGet() {
		$autoIncrement= $this->addRecord();
		$ui= new EventUI();
		$obj= $ui->get(1);
		$this->assertEquals($obj ['Event'] ['id'], 1);
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
		$ui= new EventUI();
		$paginator= $ui->table($conditions);
		$this->assertEquals($paginator ['list'] [0] ['Event'] ['id'], 1);
		$this->removeRecord($autoIncrement);
	}

	function testSave() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "events");
		
		// obj
		$bs= new EventBS();
		$obj= $bs->instance();
		$obj ['Event'] ['cod']= "mioCodTest";
		
		// save
		$ui= new EventUI();
		$id= $ui->save($obj);
		
		// search
		$search= "SELECT * FROM events WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['events'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');
		
		// delete
		MysqlUtilityTest::deleteLast($dbo, "events", "cod='mioCodTest'");
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "events", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "events", "cod='mioCodTest'");
	}

	function testEdit() {
		$autoIncrement= $this->addRecord();
		
		// obj
		$id= 1;
		$bs= new EventBS();
		$obj= $bs->unique($id);
		
		$objNew= $obj;
		$objNew ['Event'] ['cod']= "OthermioCodTest";
		
		// edit
		$ui= new EventUI();
		$id= $ui->edit($id, $objNew);
		
		// test
		$bs= new EventBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Event'] ['cod'], 'OthermioCodTest');
		
		// reset
		$ui= new EventUI();
		$id= $ui->edit($id, $obj);
		
		// test
		$bs= new EventBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Event'] ['cod'] == 'OthermioCodTest', false);
		$this->removeRecord($autoIncrement);
	}

	function testDelete() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "events");
		
		// insert
		$sql= "INSERT INTO events (id,cod,created) VALUES";
		$sql.= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
		$data= $dbo->query($sql);
		
		// search
		$search= "SELECT * FROM events WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['events'];
		$id= $result ['id'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');		
		
		// delete
		$ui= new EventUI();
		$ui->delete($id);
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "events", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "events", "cod='mioCodTest'");
	}
}