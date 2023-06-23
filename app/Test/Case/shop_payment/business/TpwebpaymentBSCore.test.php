<?php
App::uses("TpwebpaymentBS", "modules/shop_payment/business");
App::uses("MysqlUtilityTest", "Test/utility");

class TpwebpaymentBSCoreTest extends CakeTestCase {

	function addRecord() {
		$bs= new TpwebpaymentBS();
		$num= $bs->count();
		if ($num == 0) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "tpwebpayments");
			$bs= new TpwebpaymentBS();
			$obj= $bs->instance();
			$obj ['Tpwebpayment'] ['cod']= "mioCodTest";		
			$id= $bs->save($obj);
			return $autoIncrement;
		}
		return null;
	}

	function removeRecord($autoIncrement) {
		if (! empty($autoIncrement)) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			MysqlUtilityTest::deleteLast($dbo, "tpwebpayments", "cod='mioCodTest'");
			MysqlUtilityTest::resetAutoIncrement($dbo, $this, "tpwebpayments", $autoIncrement);
			MysqlUtilityTest::verifyDeleted($dbo, $this, "tpwebpayments", "cod='mioCodTest'");
		}
	}

	function testUnique() {
		$autoIncrement= $this->addRecord();
		$bs= new TpwebpaymentBS();
		$obj= $bs->unique(1);
		$this->assertEquals($obj ['Tpwebpayment'] ['id'], 1);
		$this->removeRecord($autoIncrement);
	}

	function testAll() {
		$autoIncrement= $this->addRecord();
		$bs= new TpwebpaymentBS();
		$bs->addCondition("id", 1);
		$list= $bs->all();
		$this->assertEquals($list [0] ['Tpwebpayment'] ['id'], 1);
		$this->removeRecord($autoIncrement);
	}

	function testSave() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "tpwebpayments");
		
		// obj
		$bs= new TpwebpaymentBS();
		$obj= $bs->instance();
		$obj ['Tpwebpayment'] ['cod']= "mioCodTest";		
		
		// save
		$bs= new TpwebpaymentBS();
		$id= $bs->save($obj);
		
		// search
		$search= "SELECT * FROM tpwebpayments WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['tpwebpayments'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');		
		
		// delete
		MysqlUtilityTest::deleteLast($dbo, "tpwebpayments", "cod='mioCodTest'");
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "tpwebpayments", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "tpwebpayments", "cod='mioCodTest'");
	}

	function testEdit() {
		$autoIncrement= $this->addRecord();
		
		// obj
		$id= 1;
		$bs= new TpwebpaymentBS();
		$obj= $bs->unique($id);
		
		$objNew= $obj;
		$objNew ['Tpwebpayment'] ['cod']= "OthermioCodTest";
		
		// edit
		$bs= new TpwebpaymentBS();
		$id= $bs->save($objNew);
		
		// test
		$bs= new TpwebpaymentBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Tpwebpayment'] ['cod'], 'OthermioCodTest');
		
		// reset
		$bs= new TpwebpaymentBS();
		$id= $bs->save($obj);
		
		// test
		$bs= new TpwebpaymentBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Tpwebpayment'] ['cod'] == 'OthermioCodTest', false);
		$this->removeRecord($autoIncrement);
	}

	function testDelete() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "tpwebpayments");
		
		// insert
		$sql= "INSERT INTO tpwebpayments (id,cod,created) VALUES";
		$sql.= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
		$data= $dbo->query($sql);
		
		// search
		$search= "SELECT * FROM tpwebpayments WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['tpwebpayments'];
		$id= $result ['id'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');		
		
		// delete
		$bs= new TpwebpaymentBS();
		$id= $bs->delete($id);
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "tpwebpayments", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "tpwebpayments", "cod='mioCodTest'");
	}
}