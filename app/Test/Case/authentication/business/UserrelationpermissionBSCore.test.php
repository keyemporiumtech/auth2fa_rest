<?php
App::uses("UserrelationpermissionBS", "modules/authentication/business");
App::uses("MysqlUtilityTest", "Test/utility");

class UserrelationpermissionBSCoreTest extends CakeTestCase {

	function addRecord() {
		$bs= new UserrelationpermissionBS();
		$num= $bs->count();
		if ($num == 0) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "userrelationpermissions");
			$bs= new UserrelationpermissionBS();
			$obj= $bs->instance();
			$obj ['Userrelationpermission'] ['cod']= "mioCodTest";		
			$id= $bs->save($obj);
			return $autoIncrement;
		}
		return null;
	}

	function removeRecord($autoIncrement) {
		if (! empty($autoIncrement)) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			MysqlUtilityTest::deleteLast($dbo, "userrelationpermissions", "cod='mioCodTest'");
			MysqlUtilityTest::resetAutoIncrement($dbo, $this, "userrelationpermissions", $autoIncrement);
			MysqlUtilityTest::verifyDeleted($dbo, $this, "userrelationpermissions", "cod='mioCodTest'");
		}
	}

	function testUnique() {
		$autoIncrement= $this->addRecord();
		$bs= new UserrelationpermissionBS();
		$obj= $bs->unique(1);
		$this->assertEquals($obj ['Userrelationpermission'] ['id'], 1);
		$this->removeRecord($autoIncrement);
	}

	function testAll() {
		$autoIncrement= $this->addRecord();
		$bs= new UserrelationpermissionBS();
		$bs->addCondition("id", 1);
		$list= $bs->all();
		$this->assertEquals($list [0] ['Userrelationpermission'] ['id'], 1);
		$this->removeRecord($autoIncrement);
	}

	function testSave() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "userrelationpermissions");
		
		// obj
		$bs= new UserrelationpermissionBS();
		$obj= $bs->instance();
		$obj ['Userrelationpermission'] ['cod']= "mioCodTest";		
		
		// save
		$bs= new UserrelationpermissionBS();
		$id= $bs->save($obj);
		
		// search
		$search= "SELECT * FROM userrelationpermissions WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['userrelationpermissions'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');		
		
		// delete
		MysqlUtilityTest::deleteLast($dbo, "userrelationpermissions", "cod='mioCodTest'");
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "userrelationpermissions", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "userrelationpermissions", "cod='mioCodTest'");
	}

	function testEdit() {
		$autoIncrement= $this->addRecord();
		
		// obj
		$id= 1;
		$bs= new UserrelationpermissionBS();
		$obj= $bs->unique($id);
		
		$objNew= $obj;
		$objNew ['Userrelationpermission'] ['cod']= "OthermioCodTest";
		
		// edit
		$bs= new UserrelationpermissionBS();
		$id= $bs->save($objNew);
		
		// test
		$bs= new UserrelationpermissionBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Userrelationpermission'] ['cod'], 'OthermioCodTest');
		
		// reset
		$bs= new UserrelationpermissionBS();
		$id= $bs->save($obj);
		
		// test
		$bs= new UserrelationpermissionBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Userrelationpermission'] ['cod'] == 'OthermioCodTest', false);
		$this->removeRecord($autoIncrement);
	}

	function testDelete() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "userrelationpermissions");
		
		// insert
		$sql= "INSERT INTO userrelationpermissions (id,cod,created) VALUES";
		$sql.= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
		$data= $dbo->query($sql);
		
		// search
		$search= "SELECT * FROM userrelationpermissions WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['userrelationpermissions'];
		$id= $result ['id'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');		
		
		// delete
		$bs= new UserrelationpermissionBS();
		$id= $bs->delete($id);
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "userrelationpermissions", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "userrelationpermissions", "cod='mioCodTest'");
	}
}