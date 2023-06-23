<?php
App::uses("BasketticketUI", "modules/shop_payment/delegate");
App::uses("BasketticketBS", "modules/shop_payment/business");
App::uses("MysqlUtilityTest", "Test/utility");

class BasketticketUICoreTest extends CakeTestCase {

	function addRecord() {
		$bs= new BasketticketBS();
		$num= $bs->count();
		if ($num == 0) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "baskettickets");
			$bs= new BasketticketBS();
			$obj= $bs->instance();
			$obj ['Basketticket'] ['cod']= "mioCodTest";		
			$id= $bs->save($obj);
			return $autoIncrement;
		}
		return null;
	}

	function removeRecord($autoIncrement) {
		if (! empty($autoIncrement)) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			MysqlUtilityTest::deleteLast($dbo, "baskettickets", "cod='mioCodTest'");
			MysqlUtilityTest::resetAutoIncrement($dbo, $this, "baskettickets", $autoIncrement);
			MysqlUtilityTest::verifyDeleted($dbo, $this, "baskettickets", "cod='mioCodTest'");
		}
	}

	function testGet() {
		$autoIncrement= $this->addRecord();
		$ui= new BasketticketUI();
		$obj= $ui->get(1);
		$this->assertEquals($obj ['Basketticket'] ['id'], 1);
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
		$ui= new BasketticketUI();
		$paginator= $ui->table($conditions);
		$this->assertEquals($paginator ['list'] [0] ['Basketticket'] ['id'], 1);
		$this->removeRecord($autoIncrement);
	}

	function testSave() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "baskettickets");
		
		// obj
		$bs= new BasketticketBS();
		$obj= $bs->instance();
		$obj ['Basketticket'] ['cod']= "mioCodTest";
		
		// save
		$ui= new BasketticketUI();
		$id= $ui->save($obj);
		
		// search
		$search= "SELECT * FROM baskettickets WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['baskettickets'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');
		
		// delete
		MysqlUtilityTest::deleteLast($dbo, "baskettickets", "cod='mioCodTest'");
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "baskettickets", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "baskettickets", "cod='mioCodTest'");
	}

	function testEdit() {
		$autoIncrement= $this->addRecord();
		
		// obj
		$id= 1;
		$bs= new BasketticketBS();
		$obj= $bs->unique($id);
		
		$objNew= $obj;
		$objNew ['Basketticket'] ['cod']= "OthermioCodTest";
		
		// edit
		$ui= new BasketticketUI();
		$id= $ui->edit($id, $objNew);
		
		// test
		$bs= new BasketticketBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Basketticket'] ['cod'], 'OthermioCodTest');
		
		// reset
		$ui= new BasketticketUI();
		$id= $ui->edit($id, $obj);
		
		// test
		$bs= new BasketticketBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Basketticket'] ['cod'] == 'OthermioCodTest', false);
		$this->removeRecord($autoIncrement);
	}

	function testDelete() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "baskettickets");
		
		// insert
		$sql= "INSERT INTO baskettickets (id,cod,created) VALUES";
		$sql.= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
		$data= $dbo->query($sql);
		
		// search
		$search= "SELECT * FROM baskettickets WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['baskettickets'];
		$id= $result ['id'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');		
		
		// delete
		$ui= new BasketticketUI();
		$ui->delete($id);
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "baskettickets", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "baskettickets", "cod='mioCodTest'");
	}
}