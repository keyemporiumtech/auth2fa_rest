<?php
App::uses("PaymentmethodBS", "modules/shop_payment/business");
App::uses("MysqlUtilityTest", "Test/utility");

class PaymentmethodBSCoreTest extends CakeTestCase {

	function addRecord() {
		$bs= new PaymentmethodBS();
		$num= $bs->count();
		if ($num == 0) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "paymentmethods");
			$bs= new PaymentmethodBS();
			$obj= $bs->instance();
			$obj ['Paymentmethod'] ['cod']= "mioCodTest";		
			$id= $bs->save($obj);
			return $autoIncrement;
		}
		return null;
	}

	function removeRecord($autoIncrement) {
		if (! empty($autoIncrement)) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			MysqlUtilityTest::deleteLast($dbo, "paymentmethods", "cod='mioCodTest'");
			MysqlUtilityTest::resetAutoIncrement($dbo, $this, "paymentmethods", $autoIncrement);
			MysqlUtilityTest::verifyDeleted($dbo, $this, "paymentmethods", "cod='mioCodTest'");
		}
	}

	function testUnique() {
		$autoIncrement= $this->addRecord();
		$bs= new PaymentmethodBS();
		$obj= $bs->unique(1);
		$this->assertEquals($obj ['Paymentmethod'] ['id'], 1);
		$this->removeRecord($autoIncrement);
	}

	function testAll() {
		$autoIncrement= $this->addRecord();
		$bs= new PaymentmethodBS();
		$bs->addCondition("id", 1);
		$list= $bs->all();
		$this->assertEquals($list [0] ['Paymentmethod'] ['id'], 1);
		$this->removeRecord($autoIncrement);
	}

	function testSave() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "paymentmethods");
		
		// obj
		$bs= new PaymentmethodBS();
		$obj= $bs->instance();
		$obj ['Paymentmethod'] ['cod']= "mioCodTest";		
		
		// save
		$bs= new PaymentmethodBS();
		$id= $bs->save($obj);
		
		// search
		$search= "SELECT * FROM paymentmethods WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['paymentmethods'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');		
		
		// delete
		MysqlUtilityTest::deleteLast($dbo, "paymentmethods", "cod='mioCodTest'");
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "paymentmethods", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "paymentmethods", "cod='mioCodTest'");
	}

	function testEdit() {
		$autoIncrement= $this->addRecord();
		
		// obj
		$id= 1;
		$bs= new PaymentmethodBS();
		$obj= $bs->unique($id);
		
		$objNew= $obj;
		$objNew ['Paymentmethod'] ['cod']= "OthermioCodTest";
		
		// edit
		$bs= new PaymentmethodBS();
		$id= $bs->save($objNew);
		
		// test
		$bs= new PaymentmethodBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Paymentmethod'] ['cod'], 'OthermioCodTest');
		
		// reset
		$bs= new PaymentmethodBS();
		$id= $bs->save($obj);
		
		// test
		$bs= new PaymentmethodBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Paymentmethod'] ['cod'] == 'OthermioCodTest', false);
		$this->removeRecord($autoIncrement);
	}

	function testDelete() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "paymentmethods");
		
		// insert
		$sql= "INSERT INTO paymentmethods (id,cod,created) VALUES";
		$sql.= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
		$data= $dbo->query($sql);
		
		// search
		$search= "SELECT * FROM paymentmethods WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['paymentmethods'];
		$id= $result ['id'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');		
		
		// delete
		$bs= new PaymentmethodBS();
		$id= $bs->delete($id);
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "paymentmethods", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "paymentmethods", "cod='mioCodTest'");
	}
}