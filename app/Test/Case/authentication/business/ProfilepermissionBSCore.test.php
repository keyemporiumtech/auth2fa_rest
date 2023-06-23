<?php
App::uses("ProfilepermissionBS", "modules/authentication/business");
App::uses("MysqlUtilityTest", "Test/utility");

class ProfilepermissionBSCoreTest extends CakeTestCase {

	function addRecord() {
		$bs= new ProfilepermissionBS();
		$num= $bs->count();
		if ($num == 0) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "profilepermissions");
			$bs= new ProfilepermissionBS();
			$obj= $bs->instance();
			$obj ['Profilepermission'] ['cod']= "mioCodTest";		
			$id= $bs->save($obj);
			return $autoIncrement;
		}
		return null;
	}

	function removeRecord($autoIncrement) {
		if (! empty($autoIncrement)) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			MysqlUtilityTest::deleteLast($dbo, "profilepermissions", "cod='mioCodTest'");
			MysqlUtilityTest::resetAutoIncrement($dbo, $this, "profilepermissions", $autoIncrement);
			MysqlUtilityTest::verifyDeleted($dbo, $this, "profilepermissions", "cod='mioCodTest'");
		}
	}

	function testUnique() {
		$autoIncrement= $this->addRecord();
		$bs= new ProfilepermissionBS();
		$obj= $bs->unique(1);
		$this->assertEquals($obj ['Profilepermission'] ['id'], 1);
		$this->removeRecord($autoIncrement);
	}

	function testAll() {
		$autoIncrement= $this->addRecord();
		$bs= new ProfilepermissionBS();
		$bs->addCondition("id", 1);
		$list= $bs->all();
		$this->assertEquals($list [0] ['Profilepermission'] ['id'], 1);
		$this->removeRecord($autoIncrement);
	}

	function testSave() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "profilepermissions");
		
		// obj
		$bs= new ProfilepermissionBS();
		$obj= $bs->instance();
		$obj ['Profilepermission'] ['cod']= "mioCodTest";		
		
		// save
		$bs= new ProfilepermissionBS();
		$id= $bs->save($obj);
		
		// search
		$search= "SELECT * FROM profilepermissions WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['profilepermissions'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');		
		
		// delete
		MysqlUtilityTest::deleteLast($dbo, "profilepermissions", "cod='mioCodTest'");
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "profilepermissions", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "profilepermissions", "cod='mioCodTest'");
	}

	function testEdit() {
		$autoIncrement= $this->addRecord();
		
		// obj
		$id= 1;
		$bs= new ProfilepermissionBS();
		$obj= $bs->unique($id);
		
		$objNew= $obj;
		$objNew ['Profilepermission'] ['cod']= "OthermioCodTest";
		
		// edit
		$bs= new ProfilepermissionBS();
		$id= $bs->save($objNew);
		
		// test
		$bs= new ProfilepermissionBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Profilepermission'] ['cod'], 'OthermioCodTest');
		
		// reset
		$bs= new ProfilepermissionBS();
		$id= $bs->save($obj);
		
		// test
		$bs= new ProfilepermissionBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Profilepermission'] ['cod'] == 'OthermioCodTest', false);
		$this->removeRecord($autoIncrement);
	}

	function testDelete() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "profilepermissions");
		
		// insert
		$sql= "INSERT INTO profilepermissions (id,cod,created) VALUES";
		$sql.= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
		$data= $dbo->query($sql);
		
		// search
		$search= "SELECT * FROM profilepermissions WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['profilepermissions'];
		$id= $result ['id'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');		
		
		// delete
		$bs= new ProfilepermissionBS();
		$id= $bs->delete($id);
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "profilepermissions", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "profilepermissions", "cod='mioCodTest'");
	}
}