<?php
App::uses("TicketattachmentBS", "modules/shop_warehouse/business");
App::uses("Codes", "Config/system");
App::uses("MysqlUtilityTest", "Test/utility");

class TicketattachmentControllerCoreTest extends ControllerTestCase {

	function addRecord() {
		$bs= new TicketattachmentBS();
		$num= $bs->count();
		if ($num == 0) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "ticketattachments");
			$bs= new TicketattachmentBS();
			$obj= $bs->instance();
			$obj ['Ticketattachment'] ['cod']= "mioCodTest";		
			$id= $bs->save($obj);
			return $autoIncrement;
		}
		return null;
	}

	function removeRecord($autoIncrement) {
		if (! empty($autoIncrement)) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			MysqlUtilityTest::deleteLast($dbo, "ticketattachments", "cod='mioCodTest'");
			MysqlUtilityTest::resetAutoIncrement($dbo, $this, "ticketattachments", $autoIncrement);
			MysqlUtilityTest::verifyDeleted($dbo, $this, "ticketattachments", "cod='mioCodTest'");
		}
	}

	function testGet() {
		$autoIncrement= $this->addRecord();
		$data= array (
				'id_ticketattachment' => 1 
		);
		$post1= $this->testAction('ticketattachment/get', array (
				'data' => $data,
				'return' => 'view',
				'method' => 'POST' 
		));
		
		$responseData= json_decode($post1, true);
		$this->assertEquals($responseData ['id'], 1);
		$this->assertEquals($this->controller->response->header('_headers', '') ['statuscod'], 1);
		$this->assertEquals($this->controller->response->header('_headers', '') ['responsecod'], 200);
		
		$data= array (
				'id_ticketattachment' => 9999999 
		);
		$post1= $this->testAction('ticketattachment/get', array (
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
		$post2= $this->testAction('ticketattachment/table', array (
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
		$bs= new TicketattachmentBS();
		$sql= "SELECT COUNT(*) as num FROM ticketattachments";
		$num= $bs->queryCount($sql, "num");
		
		$autoIncrement= null;
		$dbo=null;
		if ($num == 1) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "ticketattachments");
			
			// insert
			$sql= "INSERT INTO ticketattachments (id,cod,created) VALUES";
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
			$post1= $this->testAction('ticketattachment/table', array (
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
			$search= "SELECT * FROM ticketattachments WHERE cod='mioCodTest'";
			$data= $dbo->query($search);
			$result= $data [0] ['ticketattachments'];
			$id= $result ['id'];
			$this->assertEquals(! empty($data), true);
			$this->assertEquals($result ['cod'], 'mioCodTest');
			
			// delete
			$bs= new TicketattachmentBS();
			$id= $bs->delete($id);
			
			// reset
			MysqlUtilityTest::resetAutoIncrement($dbo, $this, "ticketattachments", $autoIncrement);
			
			// verify reset
			MysqlUtilityTest::verifyDeleted($dbo, $this, "ticketattachments", "cod='mioCodTest'");
		}
	}

	function testSave() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "ticketattachments");
		
		// obj
		$bs= new TicketattachmentBS();
		$obj= $bs->instance();
		$obj ['Ticketattachment'] ['cod']= "mioCodTest";
		
		// save
		$data= array (
				'ticketattachment' => json_encode($obj ['Ticketattachment']) 
		);
		$post1= $this->testAction('ticketattachment/save', array (
				'data' => $data,
				'return' => 'view',
				'method' => 'POST' 
		));
		
		$this->assertEquals(! empty($post1), 1);
		$this->assertEquals($this->controller->response->header('_headers', '') ['statuscod'], 1);
		$this->assertEquals($this->controller->response->header('_headers', '') ['responsecod'], 200);
		
		// search
		$search= "SELECT * FROM ticketattachments WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['ticketattachments'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');
		
		// delete
		MysqlUtilityTest::deleteLast($dbo, "ticketattachments", "cod='mioCodTest'");
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "ticketattachments", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "ticketattachments", "cod='mioCodTest'");
	}

	function testEdit() {
		$autoIncrement= $this->addRecord();
		
		// obj
		$id= 1;
		$bs= new TicketattachmentBS();
		$obj= $bs->unique($id);
		
		$objNew= $obj;
		$objNew ['Ticketattachment'] ['cod']= "AltramioCodTest";
		
		// edit
		$data= array (
				'id_ticketattachment' => $objNew ['Ticketattachment'] ['id'],
				'ticketattachment' => json_encode($objNew ['Ticketattachment']) 
		);
		$post1= $this->testAction('ticketattachment/edit', array (
				'data' => $data,
				'return' => 'view',
				'method' => 'POST' 
		));
		
		$this->assertEquals(! empty($post1), 1);
		$this->assertEquals($this->controller->response->header('_headers', '') ['statuscod'], 1);
		$this->assertEquals($this->controller->response->header('_headers', '') ['responsecod'], 200);
		
		// test
		$bs= new TicketattachmentBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Ticketattachment'] ['cod'], 'AltramioCodTest');
		
		// reset
		$ui= new TicketattachmentUI();
		$id= $ui->edit($id, $obj);
		
		// test
		$bs= new TicketattachmentBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Ticketattachment'] ['cod'] == 'AltramioCodTest', false);
		$this->removeRecord($autoIncrement);
	}

	function testDelete() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "ticketattachments");
		
		// insert
		$sql= "INSERT INTO ticketattachments (id,cod,created) VALUES";
		$sql.= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
		$data= $dbo->query($sql);
		
		// search
		$search= "SELECT * FROM ticketattachments WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['ticketattachments'];
		$id= $result ['id'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');
		
		// delete
		$data= array (
				'id_ticketattachment' => $id 
		);
		$post1= $this->testAction('ticketattachment/delete', array (
				'data' => $data,
				'return' => 'view',
				'method' => 'POST' 
		));
		
		$this->assertEquals(! empty($post1), 1);
		$this->assertEquals($this->controller->response->header('_headers', '') ['statuscod'], 1);
		$this->assertEquals($this->controller->response->header('_headers', '') ['responsecod'], 200);
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "ticketattachments", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "ticketattachments", "cod='mioCodTest'");
	}
}