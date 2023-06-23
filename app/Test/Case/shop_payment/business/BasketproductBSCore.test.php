<?php
App::uses("BasketproductBS", "modules/shop_payment/business");
App::uses("MysqlUtilityTest", "Test/utility");

class BasketproductBSCoreTest extends CakeTestCase {

	function addRecord() {
		$bs= new BasketproductBS();
		$num= $bs->count();
		if ($num == 0) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "basketproducts");
			$bs= new BasketproductBS();
			$obj= $bs->instance();
			$obj ['Basketproduct'] ['cod']= "mioCodTest";		
			$id= $bs->save($obj);
			return $autoIncrement;
		}
		return null;
	}

	function removeRecord($autoIncrement) {
		if (! empty($autoIncrement)) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			MysqlUtilityTest::deleteLast($dbo, "basketproducts", "cod='mioCodTest'");
			MysqlUtilityTest::resetAutoIncrement($dbo, $this, "basketproducts", $autoIncrement);
			MysqlUtilityTest::verifyDeleted($dbo, $this, "basketproducts", "cod='mioCodTest'");
		}
	}

	function testUnique() {
		$autoIncrement= $this->addRecord();
		$bs= new BasketproductBS();
		$obj= $bs->unique(1);
		$this->assertEquals($obj ['Basketproduct'] ['id'], 1);
		$this->removeRecord($autoIncrement);
	}

	function testAll() {
		$autoIncrement= $this->addRecord();
		$bs= new BasketproductBS();
		$bs->addCondition("id", 1);
		$list= $bs->all();
		$this->assertEquals($list [0] ['Basketproduct'] ['id'], 1);
		$this->removeRecord($autoIncrement);
	}

	function testSave() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "basketproducts");
		
		// obj
		$bs= new BasketproductBS();
		$obj= $bs->instance();
		$obj ['Basketproduct'] ['cod']= "mioCodTest";		
		
		// save
		$bs= new BasketproductBS();
		$id= $bs->save($obj);
		
		// search
		$search= "SELECT * FROM basketproducts WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['basketproducts'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');		
		
		// delete
		MysqlUtilityTest::deleteLast($dbo, "basketproducts", "cod='mioCodTest'");
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "basketproducts", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "basketproducts", "cod='mioCodTest'");
	}

	function testEdit() {
		$autoIncrement= $this->addRecord();
		
		// obj
		$id= 1;
		$bs= new BasketproductBS();
		$obj= $bs->unique($id);
		
		$objNew= $obj;
		$objNew ['Basketproduct'] ['cod']= "OthermioCodTest";
		
		// edit
		$bs= new BasketproductBS();
		$id= $bs->save($objNew);
		
		// test
		$bs= new BasketproductBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Basketproduct'] ['cod'], 'OthermioCodTest');
		
		// reset
		$bs= new BasketproductBS();
		$id= $bs->save($obj);
		
		// test
		$bs= new BasketproductBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Basketproduct'] ['cod'] == 'OthermioCodTest', false);
		$this->removeRecord($autoIncrement);
	}

	function testDelete() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "basketproducts");
		
		// insert
		$sql= "INSERT INTO basketproducts (id,cod,created) VALUES";
		$sql.= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
		$data= $dbo->query($sql);
		
		// search
		$search= "SELECT * FROM basketproducts WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['basketproducts'];
		$id= $result ['id'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');		
		
		// delete
		$bs= new BasketproductBS();
		$id= $bs->delete($id);
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "basketproducts", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "basketproducts", "cod='mioCodTest'");
	}
}