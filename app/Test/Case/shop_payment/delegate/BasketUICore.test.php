<?php
App::uses("BasketUI", "modules/shop_payment/delegate");
App::uses("BasketBS", "modules/shop_payment/business");
App::uses("MysqlUtilityTest", "Test/utility");

class BasketUICoreTest extends CakeTestCase {

	function addRecord() {
		$bs= new BasketBS();
		$num= $bs->count();
		if ($num == 0) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "baskets");
			$bs= new BasketBS();
			$obj= $bs->instance();
			$obj ['Basket'] ['cod']= "mioCodTest";		
			$id= $bs->save($obj);
			return $autoIncrement;
		}
		return null;
	}

	function removeRecord($autoIncrement) {
		if (! empty($autoIncrement)) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			MysqlUtilityTest::deleteLast($dbo, "baskets", "cod='mioCodTest'");
			MysqlUtilityTest::resetAutoIncrement($dbo, $this, "baskets", $autoIncrement);
			MysqlUtilityTest::verifyDeleted($dbo, $this, "baskets", "cod='mioCodTest'");
		}
	}

	function testGet() {
		$autoIncrement= $this->addRecord();
		$ui= new BasketUI();
		$obj= $ui->get(1);
		$this->assertEquals($obj ['Basket'] ['id'], 1);
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
		$ui= new BasketUI();
		$paginator= $ui->table($conditions);
		$this->assertEquals($paginator ['list'] [0] ['Basket'] ['id'], 1);
		$this->removeRecord($autoIncrement);
	}

	function testSave() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "baskets");
		
		// obj
		$bs= new BasketBS();
		$obj= $bs->instance();
		$obj ['Basket'] ['cod']= "mioCodTest";
		
		// save
		$ui= new BasketUI();
		$id= $ui->save($obj);
		
		// search
		$search= "SELECT * FROM baskets WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['baskets'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');
		
		// delete
		MysqlUtilityTest::deleteLast($dbo, "baskets", "cod='mioCodTest'");
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "baskets", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "baskets", "cod='mioCodTest'");
	}

	function testEdit() {
		$autoIncrement= $this->addRecord();
		
		// obj
		$id= 1;
		$bs= new BasketBS();
		$obj= $bs->unique($id);
		
		$objNew= $obj;
		$objNew ['Basket'] ['cod']= "OthermioCodTest";
		
		// edit
		$ui= new BasketUI();
		$id= $ui->edit($id, $objNew);
		
		// test
		$bs= new BasketBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Basket'] ['cod'], 'OthermioCodTest');
		
		// reset
		$ui= new BasketUI();
		$id= $ui->edit($id, $obj);
		
		// test
		$bs= new BasketBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Basket'] ['cod'] == 'OthermioCodTest', false);
		$this->removeRecord($autoIncrement);
	}

	function testDelete() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "baskets");
		
		// insert
		$sql= "INSERT INTO baskets (id,cod,created) VALUES";
		$sql.= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
		$data= $dbo->query($sql);
		
		// search
		$search= "SELECT * FROM baskets WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['baskets'];
		$id= $result ['id'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');		
		
		// delete
		$ui= new BasketUI();
		$ui->delete($id);
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "baskets", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "baskets", "cod='mioCodTest'");
	}
}