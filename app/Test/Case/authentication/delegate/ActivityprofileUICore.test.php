<?php
App::uses("ActivityprofileUI", "modules/authentication/delegate");
App::uses("ActivityprofileBS", "modules/authentication/business");
App::uses("MysqlUtilityTest", "Test/utility");

class ActivityprofileUICoreTest extends CakeTestCase {

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

	function testGet() {
		$autoIncrement= $this->addRecord();
		$ui= new ActivityprofileUI();
		$obj= $ui->get(1);
		$this->assertEquals($obj ['Activityprofile'] ['id'], 1);
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
		$ui= new ActivityprofileUI();
		$paginator= $ui->table($conditions);
		$this->assertEquals($paginator ['list'] [0] ['Activityprofile'] ['id'], 1);
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
		$ui= new ActivityprofileUI();
		$id= $ui->save($obj);
		
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
		$ui= new ActivityprofileUI();
		$id= $ui->edit($id, $objNew);
		
		// test
		$bs= new ActivityprofileBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Activityprofile'] ['cod'], 'OthermioCodTest');
		
		// reset
		$ui= new ActivityprofileUI();
		$id= $ui->edit($id, $obj);
		
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
		$ui= new ActivityprofileUI();
		$ui->delete($id);
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "activityprofiles", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "activityprofiles", "cod='mioCodTest'");
	}
}