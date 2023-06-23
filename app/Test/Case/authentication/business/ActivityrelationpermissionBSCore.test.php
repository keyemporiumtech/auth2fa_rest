<?php
App::uses("ActivityrelationpermissionBS", "modules/authentication/business");
App::uses("MysqlUtilityTest", "Test/utility");

class ActivityrelationpermissionBSCoreTest extends CakeTestCase {

	function addRecord() {
		$bs= new ActivityrelationpermissionBS();
		$num= $bs->count();
		if ($num == 0) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "activityrelationpermissions");
			$bs= new ActivityrelationpermissionBS();
			$obj= $bs->instance();
			$obj ['Activityrelationpermission'] ['cod']= "mioCodTest";		
			$id= $bs->save($obj);
			return $autoIncrement;
		}
		return null;
	}

	function removeRecord($autoIncrement) {
		if (! empty($autoIncrement)) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			MysqlUtilityTest::deleteLast($dbo, "activityrelationpermissions", "cod='mioCodTest'");
			MysqlUtilityTest::resetAutoIncrement($dbo, $this, "activityrelationpermissions", $autoIncrement);
			MysqlUtilityTest::verifyDeleted($dbo, $this, "activityrelationpermissions", "cod='mioCodTest'");
		}
	}

	function testUnique() {
		$autoIncrement= $this->addRecord();
		$bs= new ActivityrelationpermissionBS();
		$obj= $bs->unique(1);
		$this->assertEquals($obj ['Activityrelationpermission'] ['id'], 1);
		$this->removeRecord($autoIncrement);
	}

	function testAll() {
		$autoIncrement= $this->addRecord();
		$bs= new ActivityrelationpermissionBS();
		$bs->addCondition("id", 1);
		$list= $bs->all();
		$this->assertEquals($list [0] ['Activityrelationpermission'] ['id'], 1);
		$this->removeRecord($autoIncrement);
	}

	function testSave() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "activityrelationpermissions");
		
		// obj
		$bs= new ActivityrelationpermissionBS();
		$obj= $bs->instance();
		$obj ['Activityrelationpermission'] ['cod']= "mioCodTest";		
		
		// save
		$bs= new ActivityrelationpermissionBS();
		$id= $bs->save($obj);
		
		// search
		$search= "SELECT * FROM activityrelationpermissions WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['activityrelationpermissions'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');		
		
		// delete
		MysqlUtilityTest::deleteLast($dbo, "activityrelationpermissions", "cod='mioCodTest'");
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "activityrelationpermissions", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "activityrelationpermissions", "cod='mioCodTest'");
	}

	function testEdit() {
		$autoIncrement= $this->addRecord();
		
		// obj
		$id= 1;
		$bs= new ActivityrelationpermissionBS();
		$obj= $bs->unique($id);
		
		$objNew= $obj;
		$objNew ['Activityrelationpermission'] ['cod']= "OthermioCodTest";
		
		// edit
		$bs= new ActivityrelationpermissionBS();
		$id= $bs->save($objNew);
		
		// test
		$bs= new ActivityrelationpermissionBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Activityrelationpermission'] ['cod'], 'OthermioCodTest');
		
		// reset
		$bs= new ActivityrelationpermissionBS();
		$id= $bs->save($obj);
		
		// test
		$bs= new ActivityrelationpermissionBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Activityrelationpermission'] ['cod'] == 'OthermioCodTest', false);
		$this->removeRecord($autoIncrement);
	}

	function testDelete() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "activityrelationpermissions");
		
		// insert
		$sql= "INSERT INTO activityrelationpermissions (id,cod,created) VALUES";
		$sql.= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
		$data= $dbo->query($sql);
		
		// search
		$search= "SELECT * FROM activityrelationpermissions WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['activityrelationpermissions'];
		$id= $result ['id'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');		
		
		// delete
		$bs= new ActivityrelationpermissionBS();
		$id= $bs->delete($id);
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "activityrelationpermissions", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "activityrelationpermissions", "cod='mioCodTest'");
	}
}