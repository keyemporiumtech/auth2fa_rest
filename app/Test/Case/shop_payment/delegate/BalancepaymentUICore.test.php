<?php
App::uses("BalancepaymentUI", "modules/shop_payment/delegate");
App::uses("BalancepaymentBS", "modules/shop_payment/business");
App::uses("MysqlUtilityTest", "Test/utility");

class BalancepaymentUICoreTest extends CakeTestCase {

	function addRecord() {
		$bs= new BalancepaymentBS();
		$num= $bs->count();
		if ($num == 0) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "balancepayments");
			$bs= new BalancepaymentBS();
			$obj= $bs->instance();
			$obj ['Balancepayment'] ['cod']= "mioCodTest";		
			$id= $bs->save($obj);
			return $autoIncrement;
		}
		return null;
	}

	function removeRecord($autoIncrement) {
		if (! empty($autoIncrement)) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			MysqlUtilityTest::deleteLast($dbo, "balancepayments", "cod='mioCodTest'");
			MysqlUtilityTest::resetAutoIncrement($dbo, $this, "balancepayments", $autoIncrement);
			MysqlUtilityTest::verifyDeleted($dbo, $this, "balancepayments", "cod='mioCodTest'");
		}
	}

	function testGet() {
		$autoIncrement= $this->addRecord();
		$ui= new BalancepaymentUI();
		$obj= $ui->get(1);
		$this->assertEquals($obj ['Balancepayment'] ['id'], 1);
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
		$ui= new BalancepaymentUI();
		$paginator= $ui->table($conditions);
		$this->assertEquals($paginator ['list'] [0] ['Balancepayment'] ['id'], 1);
		$this->removeRecord($autoIncrement);
	}

	function testSave() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "balancepayments");
		
		// obj
		$bs= new BalancepaymentBS();
		$obj= $bs->instance();
		$obj ['Balancepayment'] ['cod']= "mioCodTest";
		
		// save
		$ui= new BalancepaymentUI();
		$id= $ui->save($obj);
		
		// search
		$search= "SELECT * FROM balancepayments WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['balancepayments'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');
		
		// delete
		MysqlUtilityTest::deleteLast($dbo, "balancepayments", "cod='mioCodTest'");
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "balancepayments", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "balancepayments", "cod='mioCodTest'");
	}

	function testEdit() {
		$autoIncrement= $this->addRecord();
		
		// obj
		$id= 1;
		$bs= new BalancepaymentBS();
		$obj= $bs->unique($id);
		
		$objNew= $obj;
		$objNew ['Balancepayment'] ['cod']= "OthermioCodTest";
		
		// edit
		$ui= new BalancepaymentUI();
		$id= $ui->edit($id, $objNew);
		
		// test
		$bs= new BalancepaymentBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Balancepayment'] ['cod'], 'OthermioCodTest');
		
		// reset
		$ui= new BalancepaymentUI();
		$id= $ui->edit($id, $obj);
		
		// test
		$bs= new BalancepaymentBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Balancepayment'] ['cod'] == 'OthermioCodTest', false);
		$this->removeRecord($autoIncrement);
	}

	function testDelete() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "balancepayments");
		
		// insert
		$sql= "INSERT INTO balancepayments (id,cod,created) VALUES";
		$sql.= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
		$data= $dbo->query($sql);
		
		// search
		$search= "SELECT * FROM balancepayments WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['balancepayments'];
		$id= $result ['id'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');		
		
		// delete
		$ui= new BalancepaymentUI();
		$ui->delete($id);
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "balancepayments", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "balancepayments", "cod='mioCodTest'");
	}
}