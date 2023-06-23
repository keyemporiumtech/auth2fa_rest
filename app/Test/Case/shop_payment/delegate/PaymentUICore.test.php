<?php
App::uses("PaymentUI", "modules/shop_payment/delegate");
App::uses("PaymentBS", "modules/shop_payment/business");
App::uses("MysqlUtilityTest", "Test/utility");

class PaymentUICoreTest extends CakeTestCase {

	function addRecord() {
		$bs= new PaymentBS();
		$num= $bs->count();
		if ($num == 0) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "payments");
			$bs= new PaymentBS();
			$obj= $bs->instance();
			$obj ['Payment'] ['cod']= "mioCodTest";		
			$id= $bs->save($obj);
			return $autoIncrement;
		}
		return null;
	}

	function removeRecord($autoIncrement) {
		if (! empty($autoIncrement)) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			MysqlUtilityTest::deleteLast($dbo, "payments", "cod='mioCodTest'");
			MysqlUtilityTest::resetAutoIncrement($dbo, $this, "payments", $autoIncrement);
			MysqlUtilityTest::verifyDeleted($dbo, $this, "payments", "cod='mioCodTest'");
		}
	}

	function testGet() {
		$autoIncrement= $this->addRecord();
		$ui= new PaymentUI();
		$obj= $ui->get(1);
		$this->assertEquals($obj ['Payment'] ['id'], 1);
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
		$ui= new PaymentUI();
		$paginator= $ui->table($conditions);
		$this->assertEquals($paginator ['list'] [0] ['Payment'] ['id'], 1);
		$this->removeRecord($autoIncrement);
	}

	function testSave() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "payments");
		
		// obj
		$bs= new PaymentBS();
		$obj= $bs->instance();
		$obj ['Payment'] ['cod']= "mioCodTest";
		
		// save
		$ui= new PaymentUI();
		$id= $ui->save($obj);
		
		// search
		$search= "SELECT * FROM payments WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['payments'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');
		
		// delete
		MysqlUtilityTest::deleteLast($dbo, "payments", "cod='mioCodTest'");
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "payments", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "payments", "cod='mioCodTest'");
	}

	function testEdit() {
		$autoIncrement= $this->addRecord();
		
		// obj
		$id= 1;
		$bs= new PaymentBS();
		$obj= $bs->unique($id);
		
		$objNew= $obj;
		$objNew ['Payment'] ['cod']= "OthermioCodTest";
		
		// edit
		$ui= new PaymentUI();
		$id= $ui->edit($id, $objNew);
		
		// test
		$bs= new PaymentBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Payment'] ['cod'], 'OthermioCodTest');
		
		// reset
		$ui= new PaymentUI();
		$id= $ui->edit($id, $obj);
		
		// test
		$bs= new PaymentBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Payment'] ['cod'] == 'OthermioCodTest', false);
		$this->removeRecord($autoIncrement);
	}

	function testDelete() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "payments");
		
		// insert
		$sql= "INSERT INTO payments (id,cod,created) VALUES";
		$sql.= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
		$data= $dbo->query($sql);
		
		// search
		$search= "SELECT * FROM payments WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['payments'];
		$id= $result ['id'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');		
		
		// delete
		$ui= new PaymentUI();
		$ui->delete($id);
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "payments", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "payments", "cod='mioCodTest'");
	}
}