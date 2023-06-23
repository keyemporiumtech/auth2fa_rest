<?php
App::uses("ActivityrelationUI", "modules/authentication/delegate");
App::uses("ActivityrelationBS", "modules/authentication/business");
App::uses("MysqlUtilityTest", "Test/utility");

class ActivityrelationUICoreTest extends CakeTestCase {

	function addRecord() {
		$bs= new ActivityrelationBS();
		$num= $bs->count();
		if ($num == 0) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "activityrelations");
			$bs= new ActivityrelationBS();
			$obj= $bs->instance();
			$obj ['Activityrelation'] ['cod']= "mioCodTest";		
			$id= $bs->save($obj);
			return $autoIncrement;
		}
		return null;
	}

	function removeRecord($autoIncrement) {
		if (! empty($autoIncrement)) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			MysqlUtilityTest::deleteLast($dbo, "activityrelations", "cod='mioCodTest'");
			MysqlUtilityTest::resetAutoIncrement($dbo, $this, "activityrelations", $autoIncrement);
			MysqlUtilityTest::verifyDeleted($dbo, $this, "activityrelations", "cod='mioCodTest'");
		}
	}

	function testGet() {
		$autoIncrement= $this->addRecord();
		$ui= new ActivityrelationUI();
		$obj= $ui->get(1);
		$this->assertEquals($obj ['Activityrelation'] ['id'], 1);
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
		$ui= new ActivityrelationUI();
		$paginator= $ui->table($conditions);
		$this->assertEquals($paginator ['list'] [0] ['Activityrelation'] ['id'], 1);
		$this->removeRecord($autoIncrement);
	}

	function testSave() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "activityrelations");
		
		// obj
		$bs= new ActivityrelationBS();
		$obj= $bs->instance();
		$obj ['Activityrelation'] ['cod']= "mioCodTest";
		
		// save
		$ui= new ActivityrelationUI();
		$id= $ui->save($obj);
		
		// search
		$search= "SELECT * FROM activityrelations WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['activityrelations'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');
		
		// delete
		MysqlUtilityTest::deleteLast($dbo, "activityrelations", "cod='mioCodTest'");
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "activityrelations", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "activityrelations", "cod='mioCodTest'");
	}

	function testEdit() {
		$autoIncrement= $this->addRecord();
		
		// obj
		$id= 1;
		$bs= new ActivityrelationBS();
		$obj= $bs->unique($id);
		
		$objNew= $obj;
		$objNew ['Activityrelation'] ['cod']= "OthermioCodTest";
		
		// edit
		$ui= new ActivityrelationUI();
		$id= $ui->edit($id, $objNew);
		
		// test
		$bs= new ActivityrelationBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Activityrelation'] ['cod'], 'OthermioCodTest');
		
		// reset
		$ui= new ActivityrelationUI();
		$id= $ui->edit($id, $obj);
		
		// test
		$bs= new ActivityrelationBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Activityrelation'] ['cod'] == 'OthermioCodTest', false);
		$this->removeRecord($autoIncrement);
	}

	function testDelete() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "activityrelations");
		
		// insert
		$sql= "INSERT INTO activityrelations (id,cod,created) VALUES";
		$sql.= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
		$data= $dbo->query($sql);
		
		// search
		$search= "SELECT * FROM activityrelations WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['activityrelations'];
		$id= $result ['id'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');		
		
		// delete
		$ui= new ActivityrelationUI();
		$ui->delete($id);
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "activityrelations", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "activityrelations", "cod='mioCodTest'");
	}
}