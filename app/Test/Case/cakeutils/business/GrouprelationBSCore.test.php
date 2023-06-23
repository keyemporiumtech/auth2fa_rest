<?php
App::uses("GrouprelationBS", "modules/cakeutils/business");
App::uses("MysqlUtilityTest", "Test/utility");

class GrouprelationBSCoreTest extends CakeTestCase {

	function addRecord() {
		$bs= new GrouprelationBS();
		$num= $bs->count();
		if ($num == 0) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "grouprelations");
			$bs= new GrouprelationBS();
			$obj= $bs->instance();
			$obj ['Grouprelation'] ['cod']= "mioCod";		
			$id= $bs->save($obj);
			return $autoIncrement;
		}
		return null;
	}

	function removeRecord($autoIncrement) {
		if (! empty($autoIncrement)) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			MysqlUtilityTest::deleteLast($dbo, "grouprelations", "cod='mioCod'");
			MysqlUtilityTest::resetAutoIncrement($dbo, $this, "grouprelations", $autoIncrement);
			MysqlUtilityTest::verifyDeleted($dbo, $this, "grouprelations", "cod='mioCod'");
		}
	}

	function testUnique() {
		$autoIncrement= $this->addRecord();
		$bs= new GrouprelationBS();
		$obj= $bs->unique(1);
		$this->assertEquals($obj ['Grouprelation'] ['id'], 1);
		$this->removeRecord($autoIncrement);
	}

	function testAll() {
		$autoIncrement= $this->addRecord();
		$bs= new GrouprelationBS();
		$bs->addCondition("id", 1);
		$list= $bs->all();
		$this->assertEquals($list [0] ['Grouprelation'] ['id'], 1);
		$this->removeRecord($autoIncrement);
	}

	function testSave() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "grouprelations");
		
		// obj
		$bs= new GrouprelationBS();
		$obj= $bs->instance();
		$obj ['Grouprelation'] ['cod']= "mioCod";		
		
		// save
		$bs= new GrouprelationBS();
		$id= $bs->save($obj);
		
		// search
		$search= "SELECT * FROM grouprelations WHERE cod='mioCod'";
		$data= $dbo->query($search);
		$result= $data [0] ['grouprelations'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCod');		
		
		// delete
		MysqlUtilityTest::deleteLast($dbo, "grouprelations", "cod='mioCod'");
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "grouprelations", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "grouprelations", "cod='mioCod'");
	}

	function testEdit() {
		$autoIncrement= $this->addRecord();
		
		// obj
		$id= 1;
		$bs= new GrouprelationBS();
		$obj= $bs->unique($id);
		
		$objNew= $obj;
		$objNew ['Grouprelation'] ['cod']= "OthermioCod";
		
		// edit
		$bs= new GrouprelationBS();
		$id= $bs->save($objNew);
		
		// test
		$bs= new GrouprelationBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Grouprelation'] ['cod'], 'OthermioCod');
		
		// reset
		$bs= new GrouprelationBS();
		$id= $bs->save($obj);
		
		// test
		$bs= new GrouprelationBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Grouprelation'] ['cod'] == 'OthermioCod', false);
		$this->removeRecord($autoIncrement);
	}

	function testDelete() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "grouprelations");
		
		// insert
		$sql= "INSERT INTO grouprelations (id,cod,created) VALUES";
		$sql.= " (NULL, 'mioCod', CURRENT_TIMESTAMP)";
		$data= $dbo->query($sql);
		
		// search
		$search= "SELECT * FROM grouprelations WHERE cod='mioCod'";
		$data= $dbo->query($search);
		$result= $data [0] ['grouprelations'];
		$id= $result ['id'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCod');		
		
		// delete
		$bs= new GrouprelationBS();
		$id= $bs->delete($id);
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "grouprelations", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "grouprelations", "cod='mioCod'");
	}
}