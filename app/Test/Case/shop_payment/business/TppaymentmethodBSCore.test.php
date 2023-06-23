<?php
App::uses("TppaymentmethodBS", "modules/shop_payment/business");
App::uses("MysqlUtilityTest", "Test/utility");

class TppaymentmethodBSCoreTest extends CakeTestCase {

	function addRecord() {
		$bs= new TppaymentmethodBS();
		$num= $bs->count();
		if ($num == 0) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "tppaymentmethods");
			$bs= new TppaymentmethodBS();
			$obj= $bs->instance();
			$obj ['Tppaymentmethod'] ['cod']= "mioCodTest";		
			$id= $bs->save($obj);
			return $autoIncrement;
		}
		return null;
	}

	function removeRecord($autoIncrement) {
		if (! empty($autoIncrement)) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			MysqlUtilityTest::deleteLast($dbo, "tppaymentmethods", "cod='mioCodTest'");
			MysqlUtilityTest::resetAutoIncrement($dbo, $this, "tppaymentmethods", $autoIncrement);
			MysqlUtilityTest::verifyDeleted($dbo, $this, "tppaymentmethods", "cod='mioCodTest'");
		}
	}

	function testUnique() {
		$autoIncrement= $this->addRecord();
		$bs= new TppaymentmethodBS();
		$obj= $bs->unique(1);
		$this->assertEquals($obj ['Tppaymentmethod'] ['id'], 1);
		$this->removeRecord($autoIncrement);
	}

	function testAll() {
		$autoIncrement= $this->addRecord();
		$bs= new TppaymentmethodBS();
		$bs->addCondition("id", 1);
		$list= $bs->all();
		$this->assertEquals($list [0] ['Tppaymentmethod'] ['id'], 1);
		$this->removeRecord($autoIncrement);
	}

	function testSave() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "tppaymentmethods");
		
		// obj
		$bs= new TppaymentmethodBS();
		$obj= $bs->instance();
		$obj ['Tppaymentmethod'] ['cod']= "mioCodTest";		
		
		// save
		$bs= new TppaymentmethodBS();
		$id= $bs->save($obj);
		
		// search
		$search= "SELECT * FROM tppaymentmethods WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['tppaymentmethods'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');		
		
		// delete
		MysqlUtilityTest::deleteLast($dbo, "tppaymentmethods", "cod='mioCodTest'");
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "tppaymentmethods", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "tppaymentmethods", "cod='mioCodTest'");
	}

	function testEdit() {
		$autoIncrement= $this->addRecord();
		
		// obj
		$id= 1;
		$bs= new TppaymentmethodBS();
		$obj= $bs->unique($id);
		
		$objNew= $obj;
		$objNew ['Tppaymentmethod'] ['cod']= "OthermioCodTest";
		
		// edit
		$bs= new TppaymentmethodBS();
		$id= $bs->save($objNew);
		
		// test
		$bs= new TppaymentmethodBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Tppaymentmethod'] ['cod'], 'OthermioCodTest');
		
		// reset
		$bs= new TppaymentmethodBS();
		$id= $bs->save($obj);
		
		// test
		$bs= new TppaymentmethodBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Tppaymentmethod'] ['cod'] == 'OthermioCodTest', false);
		$this->removeRecord($autoIncrement);
	}

	function testDelete() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "tppaymentmethods");
		
		// insert
		$sql= "INSERT INTO tppaymentmethods (id,cod,created) VALUES";
		$sql.= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
		$data= $dbo->query($sql);
		
		// search
		$search= "SELECT * FROM tppaymentmethods WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['tppaymentmethods'];
		$id= $result ['id'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');		
		
		// delete
		$bs= new TppaymentmethodBS();
		$id= $bs->delete($id);
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "tppaymentmethods", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "tppaymentmethods", "cod='mioCodTest'");
	}
}