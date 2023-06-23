<?php
App::uses("BasketserviceUI", "modules/shop_payment/delegate");
App::uses("BasketserviceBS", "modules/shop_payment/business");
App::uses("MysqlUtilityTest", "Test/utility");

class BasketserviceUICoreTest extends CakeTestCase {

	function addRecord() {
		$bs= new BasketserviceBS();
		$num= $bs->count();
		if ($num == 0) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "basketservices");
			$bs= new BasketserviceBS();
			$obj= $bs->instance();
			$obj ['Basketservice'] ['cod']= "mioCodTest";		
			$id= $bs->save($obj);
			return $autoIncrement;
		}
		return null;
	}

	function removeRecord($autoIncrement) {
		if (! empty($autoIncrement)) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			MysqlUtilityTest::deleteLast($dbo, "basketservices", "cod='mioCodTest'");
			MysqlUtilityTest::resetAutoIncrement($dbo, $this, "basketservices", $autoIncrement);
			MysqlUtilityTest::verifyDeleted($dbo, $this, "basketservices", "cod='mioCodTest'");
		}
	}

	function testGet() {
		$autoIncrement= $this->addRecord();
		$ui= new BasketserviceUI();
		$obj= $ui->get(1);
		$this->assertEquals($obj ['Basketservice'] ['id'], 1);
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
		$ui= new BasketserviceUI();
		$paginator= $ui->table($conditions);
		$this->assertEquals($paginator ['list'] [0] ['Basketservice'] ['id'], 1);
		$this->removeRecord($autoIncrement);
	}

	function testSave() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "basketservices");
		
		// obj
		$bs= new BasketserviceBS();
		$obj= $bs->instance();
		$obj ['Basketservice'] ['cod']= "mioCodTest";
		
		// save
		$ui= new BasketserviceUI();
		$id= $ui->save($obj);
		
		// search
		$search= "SELECT * FROM basketservices WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['basketservices'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');
		
		// delete
		MysqlUtilityTest::deleteLast($dbo, "basketservices", "cod='mioCodTest'");
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "basketservices", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "basketservices", "cod='mioCodTest'");
	}

	function testEdit() {
		$autoIncrement= $this->addRecord();
		
		// obj
		$id= 1;
		$bs= new BasketserviceBS();
		$obj= $bs->unique($id);
		
		$objNew= $obj;
		$objNew ['Basketservice'] ['cod']= "OthermioCodTest";
		
		// edit
		$ui= new BasketserviceUI();
		$id= $ui->edit($id, $objNew);
		
		// test
		$bs= new BasketserviceBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Basketservice'] ['cod'], 'OthermioCodTest');
		
		// reset
		$ui= new BasketserviceUI();
		$id= $ui->edit($id, $obj);
		
		// test
		$bs= new BasketserviceBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Basketservice'] ['cod'] == 'OthermioCodTest', false);
		$this->removeRecord($autoIncrement);
	}

	function testDelete() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "basketservices");
		
		// insert
		$sql= "INSERT INTO basketservices (id,cod,created) VALUES";
		$sql.= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
		$data= $dbo->query($sql);
		
		// search
		$search= "SELECT * FROM basketservices WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['basketservices'];
		$id= $result ['id'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');		
		
		// delete
		$ui= new BasketserviceUI();
		$ui->delete($id);
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "basketservices", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "basketservices", "cod='mioCodTest'");
	}
}