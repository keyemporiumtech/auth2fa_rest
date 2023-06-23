<?php
App::uses("ActivityprofileBS", "modules/authentication/business");
App::uses("MysqlUtilityTest", "Test/utility");

class ActivityprofileBSCoreTest extends CakeTestCase {

	function addRecord() {
		$bs= new ActivityprofileBS();
		$num= $bs->count();
		if ($num == 0) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "activityprofiles");
			$bs= new ActivityprofileBS();
			$obj= $bs->instance();
			$obj ['Activityprofile'] ['cod']= "mioCodTest";		
			$id= $bs->save($obj);
			return $autoIncrement;
		}
		return null;
	}

	function removeRecord($autoIncrement) {
		if (! empty($autoIncrement)) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			MysqlUtilityTest::deleteLast($dbo, "activityprofiles", "cod='mioCodTest'");
			MysqlUtilityTest::resetAutoIncrement($dbo, $this, "activityprofiles", $autoIncrement);
			MysqlUtilityTest::verifyDeleted($dbo, $this, "activityprofiles", "cod='mioCodTest'");
		}
	}

	function testUnique() {
		$autoIncrement= $this->addRecord();
		$bs= new ActivityprofileBS();
		$obj= $bs->unique(1);
		$this->assertEquals($obj ['Activityprofile'] ['id'], 1);
		$this->removeRecord($autoIncrement);
	}

	function testAll() {
		$autoIncrement= $this->addRecord();
		$bs= new ActivityprofileBS();
		$bs->addCondition("id", 1);
		$list= $bs->all();
		$this->assertEquals($list [0] ['Activityprofile'] ['id'], 1);
		$this->removeRecord($autoIncrement);
	}

	function testSave() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "activityprofiles");
		
		// obj
		$bs= new ActivityprofileBS();
		$obj= $bs->instance();
		$obj ['Activityprofile'] ['cod']= "mioCodTest";		
		
		// save
		$bs= new ActivityprofileBS();
		$id= $bs->save($obj);
		
		// search
		$search= "SELECT * FROM activityprofiles WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['activityprofiles'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');		
		
		// delete
		MysqlUtilityTest::deleteLast($dbo, "activityprofiles", "cod='mioCodTest'");
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "activityprofiles", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "activityprofiles", "cod='mioCodTest'");
	}

	function testEdit() {
		$autoIncrement= $this->addRecord();
		
		// obj
		$id= 1;
		$bs= new ActivityprofileBS();
		$obj= $bs->unique($id);
		
		$objNew= $obj;
		$objNew ['Activityprofile'] ['cod']= "OthermioCodTest";
		
		// edit
		$bs= new ActivityprofileBS();
		$id= $bs->save($objNew);
		
		// test
		$bs= new ActivityprofileBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Activityprofile'] ['cod'], 'OthermioCodTest');
		
		// reset
		$bs= new ActivityprofileBS();
		$id= $bs->save($obj);
		
		// test
		$bs= new ActivityprofileBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Activityprofile'] ['cod'] == 'OthermioCodTest', false);
		$this->removeRecord($autoIncrement);
	}

	function testDelete() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "activityprofiles");
		
		// insert
		$sql= "INSERT INTO activityprofiles (id,cod,created) VALUES";
		$sql.= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
		$data= $dbo->query($sql);
		
		// search
		$search= "SELECT * FROM activityprofiles WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['activityprofiles'];
		$id= $result ['id'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');		
		
		// delete
		$bs= new ActivityprofileBS();
		$id= $bs->delete($id);
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "activityprofiles", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "activityprofiles", "cod='mioCodTest'");
	}
}