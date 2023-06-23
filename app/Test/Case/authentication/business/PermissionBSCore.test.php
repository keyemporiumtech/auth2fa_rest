<?php
App::uses("PermissionBS", "modules/authentication/business");
App::uses("MysqlUtilityTest", "Test/utility");

class PermissionBSCoreTest extends CakeTestCase {

	function addRecord() {
		$bs= new PermissionBS();
		$num= $bs->count();
		if ($num == 0) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "permissions");
			$bs= new PermissionBS();
			$obj= $bs->instance();
			$obj ['Permission'] ['cod']= "mioCodTest";		
			$id= $bs->save($obj);
			return $autoIncrement;
		}
		return null;
	}

	function removeRecord($autoIncrement) {
		if (! empty($autoIncrement)) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			MysqlUtilityTest::deleteLast($dbo, "permissions", "cod='mioCodTest'");
			MysqlUtilityTest::resetAutoIncrement($dbo, $this, "permissions", $autoIncrement);
			MysqlUtilityTest::verifyDeleted($dbo, $this, "permissions", "cod='mioCodTest'");
		}
	}

	function testUnique() {
		$autoIncrement= $this->addRecord();
		$bs= new PermissionBS();
		$obj= $bs->unique(1);
		$this->assertEquals($obj ['Permission'] ['id'], 1);
		$this->removeRecord($autoIncrement);
	}

	function testAll() {
		$autoIncrement= $this->addRecord();
		$bs= new PermissionBS();
		$bs->addCondition("id", 1);
		$list= $bs->all();
		$this->assertEquals($list [0] ['Permission'] ['id'], 1);
		$this->removeRecord($autoIncrement);
	}

	function testSave() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "permissions");
		
		// obj
		$bs= new PermissionBS();
		$obj= $bs->instance();
		$obj ['Permission'] ['cod']= "mioCodTest";		
		
		// save
		$bs= new PermissionBS();
		$id= $bs->save($obj);
		
		// search
		$search= "SELECT * FROM permissions WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['permissions'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');		
		
		// delete
		MysqlUtilityTest::deleteLast($dbo, "permissions", "cod='mioCodTest'");
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "permissions", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "permissions", "cod='mioCodTest'");
	}

	function testEdit() {
		$autoIncrement= $this->addRecord();
		
		// obj
		$id= 1;
		$bs= new PermissionBS();
		$obj= $bs->unique($id);
		
		$objNew= $obj;
		$objNew ['Permission'] ['cod']= "OthermioCodTest";
		
		// edit
		$bs= new PermissionBS();
		$id= $bs->save($objNew);
		
		// test
		$bs= new PermissionBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Permission'] ['cod'], 'OthermioCodTest');
		
		// reset
		$bs= new PermissionBS();
		$id= $bs->save($obj);
		
		// test
		$bs= new PermissionBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Permission'] ['cod'] == 'OthermioCodTest', false);
		$this->removeRecord($autoIncrement);
	}

	function testDelete() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "permissions");
		
		// insert
		$sql= "INSERT INTO permissions (id,cod,created) VALUES";
		$sql.= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
		$data= $dbo->query($sql);
		
		// search
		$search= "SELECT * FROM permissions WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['permissions'];
		$id= $result ['id'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');		
		
		// delete
		$bs= new PermissionBS();
		$id= $bs->delete($id);
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "permissions", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "permissions", "cod='mioCodTest'");
	}
}