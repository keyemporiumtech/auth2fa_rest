<?php
App::uses("TickettaxBS", "modules/shop_warehouse/business");
App::uses("MysqlUtilityTest", "Test/utility");

class TickettaxBSCoreTest extends CakeTestCase {

	function addRecord() {
		$bs= new TickettaxBS();
		$num= $bs->count();
		if ($num == 0) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "tickettaxs");
			$bs= new TickettaxBS();
			$obj= $bs->instance();
			$obj ['Tickettax'] ['cod']= "mioCodTest";		
			$id= $bs->save($obj);
			return $autoIncrement;
		}
		return null;
	}

	function removeRecord($autoIncrement) {
		if (! empty($autoIncrement)) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			MysqlUtilityTest::deleteLast($dbo, "tickettaxs", "cod='mioCodTest'");
			MysqlUtilityTest::resetAutoIncrement($dbo, $this, "tickettaxs", $autoIncrement);
			MysqlUtilityTest::verifyDeleted($dbo, $this, "tickettaxs", "cod='mioCodTest'");
		}
	}

	function testUnique() {
		$autoIncrement= $this->addRecord();
		$bs= new TickettaxBS();
		$obj= $bs->unique(1);
		$this->assertEquals($obj ['Tickettax'] ['id'], 1);
		$this->removeRecord($autoIncrement);
	}

	function testAll() {
		$autoIncrement= $this->addRecord();
		$bs= new TickettaxBS();
		$bs->addCondition("id", 1);
		$list= $bs->all();
		$this->assertEquals($list [0] ['Tickettax'] ['id'], 1);
		$this->removeRecord($autoIncrement);
	}

	function testSave() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "tickettaxs");
		
		// obj
		$bs= new TickettaxBS();
		$obj= $bs->instance();
		$obj ['Tickettax'] ['cod']= "mioCodTest";		
		
		// save
		$bs= new TickettaxBS();
		$id= $bs->save($obj);
		
		// search
		$search= "SELECT * FROM tickettaxs WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['tickettaxs'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');		
		
		// delete
		MysqlUtilityTest::deleteLast($dbo, "tickettaxs", "cod='mioCodTest'");
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "tickettaxs", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "tickettaxs", "cod='mioCodTest'");
	}

	function testEdit() {
		$autoIncrement= $this->addRecord();
		
		// obj
		$id= 1;
		$bs= new TickettaxBS();
		$obj= $bs->unique($id);
		
		$objNew= $obj;
		$objNew ['Tickettax'] ['cod']= "OthermioCodTest";
		
		// edit
		$bs= new TickettaxBS();
		$id= $bs->save($objNew);
		
		// test
		$bs= new TickettaxBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Tickettax'] ['cod'], 'OthermioCodTest');
		
		// reset
		$bs= new TickettaxBS();
		$id= $bs->save($obj);
		
		// test
		$bs= new TickettaxBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Tickettax'] ['cod'] == 'OthermioCodTest', false);
		$this->removeRecord($autoIncrement);
	}

	function testDelete() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "tickettaxs");
		
		// insert
		$sql= "INSERT INTO tickettaxs (id,cod,created) VALUES";
		$sql.= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
		$data= $dbo->query($sql);
		
		// search
		$search= "SELECT * FROM tickettaxs WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['tickettaxs'];
		$id= $result ['id'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');		
		
		// delete
		$bs= new TickettaxBS();
		$id= $bs->delete($id);
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "tickettaxs", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "tickettaxs", "cod='mioCodTest'");
	}
}