<?php
App::uses("TicketBS", "modules/shop_warehouse/business");
App::uses("Codes", "Config/system");
App::uses("MysqlUtilityTest", "Test/utility");

class TicketControllerCoreTest extends ControllerTestCase {

	function addRecord() {
		$bs= new TicketBS();
		$num= $bs->count();
		if ($num == 0) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "tickets");
			$bs= new TicketBS();
			$obj= $bs->instance();
			$obj ['Ticket'] ['cod']= "mioCodTest";		
			$id= $bs->save($obj);
			return $autoIncrement;
		}
		return null;
	}

	function removeRecord($autoIncrement) {
		if (! empty($autoIncrement)) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			MysqlUtilityTest::deleteLast($dbo, "tickets", "cod='mioCodTest'");
			MysqlUtilityTest::resetAutoIncrement($dbo, $this, "tickets", $autoIncrement);
			MysqlUtilityTest::verifyDeleted($dbo, $this, "tickets", "cod='mioCodTest'");
		}
	}

	function testGet() {
		$autoIncrement= $this->addRecord();
		$data= array (
				'id_ticket' => 1 
		);
		$post1= $this->testAction('ticket/get', array (
				'data' => $data,
				'return' => 'view',
				'method' => 'POST' 
		));
		
		$responseData= json_decode($post1, true);
		$this->assertEquals($responseData ['id'], 1);
		$this->assertEquals($this->controller->response->header('_headers', '') ['statuscod'], 1);
		$this->assertEquals($this->controller->response->header('_headers', '') ['responsecod'], 200);
		
		$data= array (
				'id_ticket' => 9999999 
		);
		$post1= $this->testAction('ticket/get', array (
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
		$post2= $this->testAction('ticket/table', array (
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
		$bs= new TicketBS();
		$sql= "SELECT COUNT(*) as num FROM tickets";
		$num= $bs->queryCount($sql, "num");
		
		$autoIncrement= null;
		$dbo=null;
		if ($num == 1) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "tickets");
			
			// insert
			$sql= "INSERT INTO tickets (id,cod,created) VALUES";
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
			$post1= $this->testAction('ticket/table', array (
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
			$search= "SELECT * FROM tickets WHERE cod='mioCodTest'";
			$data= $dbo->query($search);
			$result= $data [0] ['tickets'];
			$id= $result ['id'];
			$this->assertEquals(! empty($data), true);
			$this->assertEquals($result ['cod'], 'mioCodTest');
			
			// delete
			$bs= new TicketBS();
			$id= $bs->delete($id);
			
			// reset
			MysqlUtilityTest::resetAutoIncrement($dbo, $this, "tickets", $autoIncrement);
			
			// verify reset
			MysqlUtilityTest::verifyDeleted($dbo, $this, "tickets", "cod='mioCodTest'");
		}
	}

	function testSave() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "tickets");
		
		// obj
		$bs= new TicketBS();
		$obj= $bs->instance();
		$obj ['Ticket'] ['cod']= "mioCodTest";
		
		// save
		$data= array (
				'ticket' => json_encode($obj ['Ticket']) 
		);
		$post1= $this->testAction('ticket/save', array (
				'data' => $data,
				'return' => 'view',
				'method' => 'POST' 
		));
		
		$this->assertEquals(! empty($post1), 1);
		$this->assertEquals($this->controller->response->header('_headers', '') ['statuscod'], 1);
		$this->assertEquals($this->controller->response->header('_headers', '') ['responsecod'], 200);
		
		// search
		$search= "SELECT * FROM tickets WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['tickets'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');
		
		// delete
		MysqlUtilityTest::deleteLast($dbo, "tickets", "cod='mioCodTest'");
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "tickets", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "tickets", "cod='mioCodTest'");
	}

	function testEdit() {
		$autoIncrement= $this->addRecord();
		
		// obj
		$id= 1;
		$bs= new TicketBS();
		$obj= $bs->unique($id);
		
		$objNew= $obj;
		$objNew ['Ticket'] ['cod']= "AltramioCodTest";
		
		// edit
		$data= array (
				'id_ticket' => $objNew ['Ticket'] ['id'],
				'ticket' => json_encode($objNew ['Ticket']) 
		);
		$post1= $this->testAction('ticket/edit', array (
				'data' => $data,
				'return' => 'view',
				'method' => 'POST' 
		));
		
		$this->assertEquals(! empty($post1), 1);
		$this->assertEquals($this->controller->response->header('_headers', '') ['statuscod'], 1);
		$this->assertEquals($this->controller->response->header('_headers', '') ['responsecod'], 200);
		
		// test
		$bs= new TicketBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Ticket'] ['cod'], 'AltramioCodTest');
		
		// reset
		$ui= new TicketUI();
		$id= $ui->edit($id, $obj);
		
		// test
		$bs= new TicketBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Ticket'] ['cod'] == 'AltramioCodTest', false);
		$this->removeRecord($autoIncrement);
	}

	function testDelete() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "tickets");
		
		// insert
		$sql= "INSERT INTO tickets (id,cod,created) VALUES";
		$sql.= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
		$data= $dbo->query($sql);
		
		// search
		$search= "SELECT * FROM tickets WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['tickets'];
		$id= $result ['id'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');
		
		// delete
		$data= array (
				'id_ticket' => $id 
		);
		$post1= $this->testAction('ticket/delete', array (
				'data' => $data,
				'return' => 'view',
				'method' => 'POST' 
		));
		
		$this->assertEquals(! empty($post1), 1);
		$this->assertEquals($this->controller->response->header('_headers', '') ['statuscod'], 1);
		$this->assertEquals($this->controller->response->header('_headers', '') ['responsecod'], 200);
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "tickets", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "tickets", "cod='mioCodTest'");
	}
}