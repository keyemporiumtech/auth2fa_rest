<?php
App::uses("EventreferenceUI", "modules/calendar/delegate");
App::uses("EventreferenceBS", "modules/calendar/business");
App::uses("MysqlUtilityTest", "Test/utility");

class EventreferenceUICoreTest extends CakeTestCase {

	function addRecord() {
		$bs= new EventreferenceBS();
		$num= $bs->count();
		if ($num == 0) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "eventreferences");
			$bs= new EventreferenceBS();
			$obj= $bs->instance();
			$obj ['Eventreference'] ['cod']= "mioCodTest";		
			$id= $bs->save($obj);
			return $autoIncrement;
		}
		return null;
	}

	function removeRecord($autoIncrement) {
		if (! empty($autoIncrement)) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			MysqlUtilityTest::deleteLast($dbo, "eventreferences", "cod='mioCodTest'");
			MysqlUtilityTest::resetAutoIncrement($dbo, $this, "eventreferences", $autoIncrement);
			MysqlUtilityTest::verifyDeleted($dbo, $this, "eventreferences", "cod='mioCodTest'");
		}
	}

	function testGet() {
		$autoIncrement= $this->addRecord();
		$ui= new EventreferenceUI();
		$obj= $ui->get(1);
		$this->assertEquals($obj ['Eventreference'] ['id'], 1);
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
		$ui= new EventreferenceUI();
		$paginator= $ui->table($conditions);
		$this->assertEquals($paginator ['list'] [0] ['Eventreference'] ['id'], 1);
		$this->removeRecord($autoIncrement);
	}

	function testSave() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "eventreferences");
		
		// obj
		$bs= new EventreferenceBS();
		$obj= $bs->instance();
		$obj ['Eventreference'] ['cod']= "mioCodTest";
		
		// save
		$ui= new EventreferenceUI();
		$id= $ui->save($obj);
		
		// search
		$search= "SELECT * FROM eventreferences WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['eventreferences'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');
		
		// delete
		MysqlUtilityTest::deleteLast($dbo, "eventreferences", "cod='mioCodTest'");
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "eventreferences", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "eventreferences", "cod='mioCodTest'");
	}

	function testEdit() {
		$autoIncrement= $this->addRecord();
		
		// obj
		$id= 1;
		$bs= new EventreferenceBS();
		$obj= $bs->unique($id);
		
		$objNew= $obj;
		$objNew ['Eventreference'] ['cod']= "OthermioCodTest";
		
		// edit
		$ui= new EventreferenceUI();
		$id= $ui->edit($id, $objNew);
		
		// test
		$bs= new EventreferenceBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Eventreference'] ['cod'], 'OthermioCodTest');
		
		// reset
		$ui= new EventreferenceUI();
		$id= $ui->edit($id, $obj);
		
		// test
		$bs= new EventreferenceBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Eventreference'] ['cod'] == 'OthermioCodTest', false);
		$this->removeRecord($autoIncrement);
	}

	function testDelete() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "eventreferences");
		
		// insert
		$sql= "INSERT INTO eventreferences (id,cod,created) VALUES";
		$sql.= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
		$data= $dbo->query($sql);
		
		// search
		$search= "SELECT * FROM eventreferences WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['eventreferences'];
		$id= $result ['id'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');		
		
		// delete
		$ui= new EventreferenceUI();
		$ui->delete($id);
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "eventreferences", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "eventreferences", "cod='mioCodTest'");
	}
}