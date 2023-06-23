<?php
App::uses("UserrelationBS", "modules/authentication/business");
App::uses("MysqlUtilityTest", "Test/utility");

class UserrelationBSCoreTest extends CakeTestCase {

	function addRecord() {
		$bs= new UserrelationBS();
		$num= $bs->count();
		if ($num == 0) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "userrelations");
			$bs= new UserrelationBS();
			$obj= $bs->instance();
			$obj ['Userrelation'] ['cod']= "mioCodTest";		
			$id= $bs->save($obj);
			return $autoIncrement;
		}
		return null;
	}

	function removeRecord($autoIncrement) {
		if (! empty($autoIncrement)) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			MysqlUtilityTest::deleteLast($dbo, "userrelations", "cod='mioCodTest'");
			MysqlUtilityTest::resetAutoIncrement($dbo, $this, "userrelations", $autoIncrement);
			MysqlUtilityTest::verifyDeleted($dbo, $this, "userrelations", "cod='mioCodTest'");
		}
	}

	function testUnique() {
		$autoIncrement= $this->addRecord();
		$bs= new UserrelationBS();
		$obj= $bs->unique(1);
		$this->assertEquals($obj ['Userrelation'] ['id'], 1);
		$this->removeRecord($autoIncrement);
	}

	function testAll() {
		$autoIncrement= $this->addRecord();
		$bs= new UserrelationBS();
		$bs->addCondition("id", 1);
		$list= $bs->all();
		$this->assertEquals($list [0] ['Userrelation'] ['id'], 1);
		$this->removeRecord($autoIncrement);
	}

	function testSave() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "userrelations");
		
		// obj
		$bs= new UserrelationBS();
		$obj= $bs->instance();
		$obj ['Userrelation'] ['cod']= "mioCodTest";		
		
		// save
		$bs= new UserrelationBS();
		$id= $bs->save($obj);
		
		// search
		$search= "SELECT * FROM userrelations WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['userrelations'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');		
		
		// delete
		MysqlUtilityTest::deleteLast($dbo, "userrelations", "cod='mioCodTest'");
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "userrelations", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "userrelations", "cod='mioCodTest'");
	}

	function testEdit() {
		$autoIncrement= $this->addRecord();
		
		// obj
		$id= 1;
		$bs= new UserrelationBS();
		$obj= $bs->unique($id);
		
		$objNew= $obj;
		$objNew ['Userrelation'] ['cod']= "OthermioCodTest";
		
		// edit
		$bs= new UserrelationBS();
		$id= $bs->save($objNew);
		
		// test
		$bs= new UserrelationBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Userrelation'] ['cod'], 'OthermioCodTest');
		
		// reset
		$bs= new UserrelationBS();
		$id= $bs->save($obj);
		
		// test
		$bs= new UserrelationBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Userrelation'] ['cod'] == 'OthermioCodTest', false);
		$this->removeRecord($autoIncrement);
	}

	function testDelete() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "userrelations");
		
		// insert
		$sql= "INSERT INTO userrelations (id,cod,created) VALUES";
		$sql.= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
		$data= $dbo->query($sql);
		
		// search
		$search= "SELECT * FROM userrelations WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['userrelations'];
		$id= $result ['id'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');		
		
		// delete
		$bs= new UserrelationBS();
		$id= $bs->delete($id);
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "userrelations", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "userrelations", "cod='mioCodTest'");
	}
}