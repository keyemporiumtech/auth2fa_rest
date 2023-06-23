<?php
App::uses("BalanceBS", "modules/shop_payment/business");
App::uses("MysqlUtilityTest", "Test/utility");

class BalanceBSCoreTest extends CakeTestCase {

	function addRecord() {
		$bs= new BalanceBS();
		$num= $bs->count();
		if ($num == 0) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "balances");
			$bs= new BalanceBS();
			$obj= $bs->instance();
			$obj ['Balance'] ['cod']= "mioCodTest";		
			$id= $bs->save($obj);
			return $autoIncrement;
		}
		return null;
	}

	function removeRecord($autoIncrement) {
		if (! empty($autoIncrement)) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			MysqlUtilityTest::deleteLast($dbo, "balances", "cod='mioCodTest'");
			MysqlUtilityTest::resetAutoIncrement($dbo, $this, "balances", $autoIncrement);
			MysqlUtilityTest::verifyDeleted($dbo, $this, "balances", "cod='mioCodTest'");
		}
	}

	function testUnique() {
		$autoIncrement= $this->addRecord();
		$bs= new BalanceBS();
		$obj= $bs->unique(1);
		$this->assertEquals($obj ['Balance'] ['id'], 1);
		$this->removeRecord($autoIncrement);
	}

	function testAll() {
		$autoIncrement= $this->addRecord();
		$bs= new BalanceBS();
		$bs->addCondition("id", 1);
		$list= $bs->all();
		$this->assertEquals($list [0] ['Balance'] ['id'], 1);
		$this->removeRecord($autoIncrement);
	}

	function testSave() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "balances");
		
		// obj
		$bs= new BalanceBS();
		$obj= $bs->instance();
		$obj ['Balance'] ['cod']= "mioCodTest";		
		
		// save
		$bs= new BalanceBS();
		$id= $bs->save($obj);
		
		// search
		$search= "SELECT * FROM balances WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['balances'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');		
		
		// delete
		MysqlUtilityTest::deleteLast($dbo, "balances", "cod='mioCodTest'");
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "balances", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "balances", "cod='mioCodTest'");
	}

	function testEdit() {
		$autoIncrement= $this->addRecord();
		
		// obj
		$id= 1;
		$bs= new BalanceBS();
		$obj= $bs->unique($id);
		
		$objNew= $obj;
		$objNew ['Balance'] ['cod']= "OthermioCodTest";
		
		// edit
		$bs= new BalanceBS();
		$id= $bs->save($objNew);
		
		// test
		$bs= new BalanceBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Balance'] ['cod'], 'OthermioCodTest');
		
		// reset
		$bs= new BalanceBS();
		$id= $bs->save($obj);
		
		// test
		$bs= new BalanceBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Balance'] ['cod'] == 'OthermioCodTest', false);
		$this->removeRecord($autoIncrement);
	}

	function testDelete() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "balances");
		
		// insert
		$sql= "INSERT INTO balances (id,cod,created) VALUES";
		$sql.= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
		$data= $dbo->query($sql);
		
		// search
		$search= "SELECT * FROM balances WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['balances'];
		$id= $result ['id'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');		
		
		// delete
		$bs= new BalanceBS();
		$id= $bs->delete($id);
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "balances", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "balances", "cod='mioCodTest'");
	}
}