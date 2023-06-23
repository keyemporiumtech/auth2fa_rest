<?php
App::uses("BalancepaymentBS", "modules/shop_payment/business");
App::uses("Codes", "Config/system");
App::uses("MysqlUtilityTest", "Test/utility");

class BalancepaymentControllerCoreTest extends ControllerTestCase {

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

	function testGet() {
		$autoIncrement= $this->addRecord();
		$data= array (
				'id_balancepayment' => 1 
		);
		$post1= $this->testAction('balancepayment/get', array (
				'data' => $data,
				'return' => 'view',
				'method' => 'POST' 
		));
		
		$responseData= json_decode($post1, true);
		$this->assertEquals($responseData ['id'], 1);
		$this->assertEquals($this->controller->response->header('_headers', '') ['statuscod'], 1);
		$this->assertEquals($this->controller->response->header('_headers', '') ['responsecod'], 200);
		
		$data= array (
				'id_balancepayment' => 9999999 
		);
		$post1= $this->testAction('balancepayment/get', array (
				'data' => $data,
				'return' => 'view',
				'method' => 'POST' 
		));
		$responseData= json_decode($post1, true);
		$this->assertEquals($responseData, null);
		$this->assertEquals($this->controller->response->header('_headers', '') ['statuscod'], - 1);
		$this->assertEquals($this->controller->response->header('_headers', '') ['responsecod'], 500);
		$this->assertEquals($this->controller->response->header('_headers', '') ['exceptioncod'], Codes::get("EXCEPTION_GENERIC"));
		$this->removeRecord($autoIncrement);
	}

	function testTable() {
		$autoIncrement= $this->addRecord();
		// CONDITIONS
		$condition= new DBCondition();
		$condition->key= "id";
		$condition->value= 99999;
		$conditions= array (
				$condition 
		);
		
		$data= array (
				'filters' => json_encode($conditions) 
		);
		$post2= $this->testAction('balancepayment/table', array (
				'data' => $data,
				'return' => 'view',
				'method' => 'POST' 
		));
		
		$paginator= json_decode($post2, true);
		$this->assertEquals(count($paginator ['list']), 0);
		$this->assertEquals($paginator ['count'], 0);
		$this->assertEquals($paginator ['pages'], 0);
		$this->removeRecord($autoIncrement);
	}

	function testPaginate() {
		$bs= new BalancepaymentBS();
		$sql= "SELECT COUNT(*) as num FROM balancepayments";
		$num= $bs->queryCount($sql, "num");
		
		$autoIncrement= null;
		$dbo=null;
		if ($num == 1) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "balancepayments");
			
			// insert
			$sql= "INSERT INTO balancepayments (id,cod,created) VALUES";
			$sql.= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
			$data= $dbo->query($sql);
		} elseif ($num == 0) {
			$this->assertEquals($num, 0);
		} else {
			
			// pagination
			$paginate= new DBPaginate();
			$paginate->limit= 1;
			$paginate->page= 2;
			
			$data= array (
					'paginate' => json_encode($paginate) 
			);
			$post1= $this->testAction('balancepayment/table', array (
					'data' => $data,
					'return' => 'view',
					'method' => 'POST' 
			));
			
			$paginator= json_decode($post1, true);
			$this->assertEquals($paginator ['list'] [0] ['id'] > 1, true);
			$this->assertEquals(count($paginator ['list']), 1);
			$this->assertEquals($paginator ['count'], $num);
			$this->assertEquals($paginator ['pages'], $num);
		}
		if (! empty($autoIncrement)) {
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

	function testSave() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "balancepayments");
		
		// obj
		$bs= new BalancepaymentBS();
		$obj= $bs->instance();
		$obj ['Balancepayment'] ['cod']= "mioCodTest";
		
		// save
		$data= array (
				'balancepayment' => json_encode($obj ['Balancepayment']) 
		);
		$post1= $this->testAction('balancepayment/save', array (
				'data' => $data,
				'return' => 'view',
				'method' => 'POST' 
		));
		
		$this->assertEquals(! empty($post1), 1);
		$this->assertEquals($this->controller->response->header('_headers', '') ['statuscod'], 1);
		$this->assertEquals($this->controller->response->header('_headers', '') ['responsecod'], 200);
		
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
		$objNew ['Balancepayment'] ['cod']= "AltramioCodTest";
		
		// edit
		$data= array (
				'id_balancepayment' => $objNew ['Balancepayment'] ['id'],
				'balancepayment' => json_encode($objNew ['Balancepayment']) 
		);
		$post1= $this->testAction('balancepayment/edit', array (
				'data' => $data,
				'return' => 'view',
				'method' => 'POST' 
		));
		
		$this->assertEquals(! empty($post1), 1);
		$this->assertEquals($this->controller->response->header('_headers', '') ['statuscod'], 1);
		$this->assertEquals($this->controller->response->header('_headers', '') ['responsecod'], 200);
		
		// test
		$bs= new BalancepaymentBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Balancepayment'] ['cod'], 'AltramioCodTest');
		
		// reset
		$ui= new BalancepaymentUI();
		$id= $ui->edit($id, $obj);
		
		// test
		$bs= new BalancepaymentBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Balancepayment'] ['cod'] == 'AltramioCodTest', false);
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
		$data= array (
				'id_balancepayment' => $id 
		);
		$post1= $this->testAction('balancepayment/delete', array (
				'data' => $data,
				'return' => 'view',
				'method' => 'POST' 
		));
		
		$this->assertEquals(! empty($post1), 1);
		$this->assertEquals($this->controller->response->header('_headers', '') ['statuscod'], 1);
		$this->assertEquals($this->controller->response->header('_headers', '') ['responsecod'], 200);
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "balancepayments", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "balancepayments", "cod='mioCodTest'");
	}
}