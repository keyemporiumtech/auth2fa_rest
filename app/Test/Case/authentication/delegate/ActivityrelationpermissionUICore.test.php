<?php
App::uses("ActivityrelationpermissionUI", "modules/authentication/delegate");
App::uses("ActivityrelationpermissionBS", "modules/authentication/business");
App::uses("MysqlUtilityTest", "Test/utility");

class ActivityrelationpermissionUICoreTest extends CakeTestCase {

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

	function testGet() {
		$autoIncrement= $this->addRecord();
		$ui= new ActivityrelationpermissionUI();
		$obj= $ui->get(1);
		$this->assertEquals($obj ['Activityrelationpermission'] ['id'], 1);
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
		$ui= new ActivityrelationpermissionUI();
		$paginator= $ui->table($conditions);
		$this->assertEquals($paginator ['list'] [0] ['Activityrelationpermission'] ['id'], 1);
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
		$ui= new ActivityrelationpermissionUI();
		$id= $ui->save($obj);
		
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
		$ui= new ActivityrelationpermissionUI();
		$id= $ui->edit($id, $objNew);
		
		// test
		$bs= new ActivityrelationpermissionBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Activityrelationpermission'] ['cod'], 'OthermioCodTest');
		
		// reset
		$ui= new ActivityrelationpermissionUI();
		$id= $ui->edit($id, $obj);
		
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
		$ui= new ActivityrelationpermissionUI();
		$ui->delete($id);
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "activityrelationpermissions", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "activityrelationpermissions", "cod='mioCodTest'");
	}
}