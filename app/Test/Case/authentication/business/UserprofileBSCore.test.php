<?php
App::uses("UserprofileBS", "modules/authentication/business");
App::uses("MysqlUtilityTest", "Test/utility");

class UserprofileBSCoreTest extends CakeTestCase {

	function addRecord() {
		$bs= new UserprofileBS();
		$num= $bs->count();
		if ($num == 0) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "userprofiles");
			$bs= new UserprofileBS();
			$obj= $bs->instance();
			$obj ['Userprofile'] ['cod']= "mioCodTest";		
			$id= $bs->save($obj);
			return $autoIncrement;
		}
		return null;
	}

	function removeRecord($autoIncrement) {
		if (! empty($autoIncrement)) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			MysqlUtilityTest::deleteLast($dbo, "userprofiles", "cod='mioCodTest'");
			MysqlUtilityTest::resetAutoIncrement($dbo, $this, "userprofiles", $autoIncrement);
			MysqlUtilityTest::verifyDeleted($dbo, $this, "userprofiles", "cod='mioCodTest'");
		}
	}

	function testUnique() {
		$autoIncrement= $this->addRecord();
		$bs= new UserprofileBS();
		$obj= $bs->unique(1);
		$this->assertEquals($obj ['Userprofile'] ['id'], 1);
		$this->removeRecord($autoIncrement);
	}

	function testAll() {
		$autoIncrement= $this->addRecord();
		$bs= new UserprofileBS();
		$bs->addCondition("id", 1);
		$list= $bs->all();
		$this->assertEquals($list [0] ['Userprofile'] ['id'], 1);
		$this->removeRecord($autoIncrement);
	}

	function testSave() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "userprofiles");
		
		// obj
		$bs= new UserprofileBS();
		$obj= $bs->instance();
		$obj ['Userprofile'] ['cod']= "mioCodTest";		
		
		// save
		$bs= new UserprofileBS();
		$id= $bs->save($obj);
		
		// search
		$search= "SELECT * FROM userprofiles WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['userprofiles'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');		
		
		// delete
		MysqlUtilityTest::deleteLast($dbo, "userprofiles", "cod='mioCodTest'");
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "userprofiles", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "userprofiles", "cod='mioCodTest'");
	}

	function testEdit() {
		$autoIncrement= $this->addRecord();
		
		// obj
		$id= 1;
		$bs= new UserprofileBS();
		$obj= $bs->unique($id);
		
		$objNew= $obj;
		$objNew ['Userprofile'] ['cod']= "OthermioCodTest";
		
		// edit
		$bs= new UserprofileBS();
		$id= $bs->save($objNew);
		
		// test
		$bs= new UserprofileBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Userprofile'] ['cod'], 'OthermioCodTest');
		
		// reset
		$bs= new UserprofileBS();
		$id= $bs->save($obj);
		
		// test
		$bs= new UserprofileBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Userprofile'] ['cod'] == 'OthermioCodTest', false);
		$this->removeRecord($autoIncrement);
	}

	function testDelete() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "userprofiles");
		
		// insert
		$sql= "INSERT INTO userprofiles (id,cod,created) VALUES";
		$sql.= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
		$data= $dbo->query($sql);
		
		// search
		$search= "SELECT * FROM userprofiles WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['userprofiles'];
		$id= $result ['id'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');		
		
		// delete
		$bs= new UserprofileBS();
		$id= $bs->delete($id);
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "userprofiles", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "userprofiles", "cod='mioCodTest'");
	}
}