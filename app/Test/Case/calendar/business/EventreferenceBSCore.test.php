<?php
App::uses("EventreferenceBS", "modules/calendar/business");
App::uses("MysqlUtilityTest", "Test/utility");

class EventreferenceBSCoreTest extends CakeTestCase {

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

	function testUnique() {
		$autoIncrement= $this->addRecord();
		$bs= new EventreferenceBS();
		$obj= $bs->unique(1);
		$this->assertEquals($obj ['Eventreference'] ['id'], 1);
		$this->removeRecord($autoIncrement);
	}

	function testAll() {
		$autoIncrement= $this->addRecord();
		$bs= new EventreferenceBS();
		$bs->addCondition("id", 1);
		$list= $bs->all();
		$this->assertEquals($list [0] ['Eventreference'] ['id'], 1);
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
		$bs= new EventreferenceBS();
		$id= $bs->save($obj);
		
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
		$bs= new EventreferenceBS();
		$id= $bs->save($objNew);
		
		// test
		$bs= new EventreferenceBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Eventreference'] ['cod'], 'OthermioCodTest');
		
		// reset
		$bs= new EventreferenceBS();
		$id= $bs->save($obj);
		
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
		$bs= new EventreferenceBS();
		$id= $bs->delete($id);
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "eventreferences", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "eventreferences", "cod='mioCodTest'");
	}
}