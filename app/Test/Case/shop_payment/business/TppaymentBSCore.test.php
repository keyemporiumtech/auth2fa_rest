<?php
App::uses("TppaymentBS", "modules/shop_payment/business");
App::uses("MysqlUtilityTest", "Test/utility");

class TppaymentBSCoreTest extends CakeTestCase {

	function addRecord() {
		$bs= new TppaymentBS();
		$num= $bs->count();
		if ($num == 0) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "tppayments");
			$bs= new TppaymentBS();
			$obj= $bs->instance();
			$obj ['Tppayment'] ['cod']= "mioCodTest";		
			$id= $bs->save($obj);
			return $autoIncrement;
		}
		return null;
	}

	function removeRecord($autoIncrement) {
		if (! empty($autoIncrement)) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			MysqlUtilityTest::deleteLast($dbo, "tppayments", "cod='mioCodTest'");
			MysqlUtilityTest::resetAutoIncrement($dbo, $this, "tppayments", $autoIncrement);
			MysqlUtilityTest::verifyDeleted($dbo, $this, "tppayments", "cod='mioCodTest'");
		}
	}

	function testUnique() {
		$autoIncrement= $this->addRecord();
		$bs= new TppaymentBS();
		$obj= $bs->unique(1);
		$this->assertEquals($obj ['Tppayment'] ['id'], 1);
		$this->removeRecord($autoIncrement);
	}

	function testAll() {
		$autoIncrement= $this->addRecord();
		$bs= new TppaymentBS();
		$bs->addCondition("id", 1);
		$list= $bs->all();
		$this->assertEquals($list [0] ['Tppayment'] ['id'], 1);
		$this->removeRecord($autoIncrement);
	}

	function testSave() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "tppayments");
		
		// obj
		$bs= new TppaymentBS();
		$obj= $bs->instance();
		$obj ['Tppayment'] ['cod']= "mioCodTest";		
		
		// save
		$bs= new TppaymentBS();
		$id= $bs->save($obj);
		
		// search
		$search= "SELECT * FROM tppayments WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['tppayments'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');		
		
		// delete
		MysqlUtilityTest::deleteLast($dbo, "tppayments", "cod='mioCodTest'");
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "tppayments", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "tppayments", "cod='mioCodTest'");
	}

	function testEdit() {
		$autoIncrement= $this->addRecord();
		
		// obj
		$id= 1;
		$bs= new TppaymentBS();
		$obj= $bs->unique($id);
		
		$objNew= $obj;
		$objNew ['Tppayment'] ['cod']= "OthermioCodTest";
		
		// edit
		$bs= new TppaymentBS();
		$id= $bs->save($objNew);
		
		// test
		$bs= new TppaymentBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Tppayment'] ['cod'], 'OthermioCodTest');
		
		// reset
		$bs= new TppaymentBS();
		$id= $bs->save($obj);
		
		// test
		$bs= new TppaymentBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Tppayment'] ['cod'] == 'OthermioCodTest', false);
		$this->removeRecord($autoIncrement);
	}

	function testDelete() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "tppayments");
		
		// insert
		$sql= "INSERT INTO tppayments (id,cod,created) VALUES";
		$sql.= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
		$data= $dbo->query($sql);
		
		// search
		$search= "SELECT * FROM tppayments WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['tppayments'];
		$id= $result ['id'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');		
		
		// delete
		$bs= new TppaymentBS();
		$id= $bs->delete($id);
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "tppayments", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "tppayments", "cod='mioCodTest'");
	}
}