<?php
App::uses("BalancepaymentBS", "modules/shop_payment/business");
App::uses("MysqlUtilityTest", "Test/utility");

class BalancepaymentBSCoreTest extends CakeTestCase {

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

	function testUnique() {
		$autoIncrement= $this->addRecord();
		$bs= new BalancepaymentBS();
		$obj= $bs->unique(1);
		$this->assertEquals($obj ['Balancepayment'] ['id'], 1);
		$this->removeRecord($autoIncrement);
	}

	function testAll() {
		$autoIncrement= $this->addRecord();
		$bs= new BalancepaymentBS();
		$bs->addCondition("id", 1);
		$list= $bs->all();
		$this->assertEquals($list [0] ['Balancepayment'] ['id'], 1);
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
		$bs= new BalancepaymentBS();
		$id= $bs->save($obj);
		
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
		$bs= new BalancepaymentBS();
		$id= $bs->save($objNew);
		
		// test
		$bs= new BalancepaymentBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Balancepayment'] ['cod'], 'OthermioCodTest');
		
		// reset
		$bs= new BalancepaymentBS();
		$id= $bs->save($obj);
		
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
		$bs= new BalancepaymentBS();
		$id= $bs->delete($id);
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "balancepayments", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "balancepayments", "cod='mioCodTest'");
	}
}