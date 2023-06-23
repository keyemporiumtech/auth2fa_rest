<?php
App::uses("UserrelationpermissionBS", "modules/authentication/business");
App::uses("Codes", "Config/system");
App::uses("MysqlUtilityTest", "Test/utility");

class UserrelationpermissionControllerCoreTest extends ControllerTestCase {

	function addRecord() {
		$bs= new UserrelationpermissionBS();
		$num= $bs->count();
		if ($num == 0) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "userrelationpermissions");
			$bs= new UserrelationpermissionBS();
			$obj= $bs->instance();
			$obj ['Userrelationpermission'] ['cod']= "mioCodTest";		
			$id= $bs->save($obj);
			return $autoIncrement;
		}
		return null;
	}

	function removeRecord($autoIncrement) {
		if (! empty($autoIncrement)) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			MysqlUtilityTest::deleteLast($dbo, "userrelationpermissions", "cod='mioCodTest'");
			MysqlUtilityTest::resetAutoIncrement($dbo, $this, "userrelationpermissions", $autoIncrement);
			MysqlUtilityTest::verifyDeleted($dbo, $this, "userrelationpermissions", "cod='mioCodTest'");
		}
	}

	function testGet() {
		$autoIncrement= $this->addRecord();
		$data= array (
				'id_userrelationpermission' => 1 
		);
		$post1= $this->testAction('userrelationpermission/get', array (
				'data' => $data,
				'return' => 'view',
				'method' => 'POST' 
		));
		
		$responseData= json_decode($post1, true);
		$this->assertEquals($responseData ['id'], 1);
		$this->assertEquals($this->controller->response->header('_headers', '') ['statuscod'], 1);
		$this->assertEquals($this->controller->response->header('_headers', '') ['responsecod'], 200);
		
		$data= array (
				'id_userrelationpermission' => 9999999 
		);
		$post1= $this->testAction('userrelationpermission/get', array (
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
		$post2= $this->testAction('userrelationpermission/table', array (
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
		$bs= new UserrelationpermissionBS();
		$sql= "SELECT COUNT(*) as num FROM userrelationpermissions";
		$num= $bs->queryCount($sql, "num");
		
		$autoIncrement= null;
		$dbo=null;
		if ($num == 1) {
			/** @var \Cake\Model\Datasource\DboSource */
			$dbo= ConnectionManager::getDataSource("default");
			$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "userrelationpermissions");
			
			// insert
			$sql= "INSERT INTO userrelationpermissions (id,cod,created) VALUES";
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
			$post1= $this->testAction('userrelationpermission/table', array (
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
			$search= "SELECT * FROM userrelationpermissions WHERE cod='mioCodTest'";
			$data= $dbo->query($search);
			$result= $data [0] ['userrelationpermissions'];
			$id= $result ['id'];
			$this->assertEquals(! empty($data), true);
			$this->assertEquals($result ['cod'], 'mioCodTest');
			
			// delete
			$bs= new UserrelationpermissionBS();
			$id= $bs->delete($id);
			
			// reset
			MysqlUtilityTest::resetAutoIncrement($dbo, $this, "userrelationpermissions", $autoIncrement);
			
			// verify reset
			MysqlUtilityTest::verifyDeleted($dbo, $this, "userrelationpermissions", "cod='mioCodTest'");
		}
	}

	function testSave() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "userrelationpermissions");
		
		// obj
		$bs= new UserrelationpermissionBS();
		$obj= $bs->instance();
		$obj ['Userrelationpermission'] ['cod']= "mioCodTest";
		
		// save
		$data= array (
				'userrelationpermission' => json_encode($obj ['Userrelationpermission']) 
		);
		$post1= $this->testAction('userrelationpermission/save', array (
				'data' => $data,
				'return' => 'view',
				'method' => 'POST' 
		));
		
		$this->assertEquals(! empty($post1), 1);
		$this->assertEquals($this->controller->response->header('_headers', '') ['statuscod'], 1);
		$this->assertEquals($this->controller->response->header('_headers', '') ['responsecod'], 200);
		
		// search
		$search= "SELECT * FROM userrelationpermissions WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['userrelationpermissions'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');
		
		// delete
		MysqlUtilityTest::deleteLast($dbo, "userrelationpermissions", "cod='mioCodTest'");
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "userrelationpermissions", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "userrelationpermissions", "cod='mioCodTest'");
	}

	function testEdit() {
		$autoIncrement= $this->addRecord();
		
		// obj
		$id= 1;
		$bs= new UserrelationpermissionBS();
		$obj= $bs->unique($id);
		
		$objNew= $obj;
		$objNew ['Userrelationpermission'] ['cod']= "AltramioCodTest";
		
		// edit
		$data= array (
				'id_userrelationpermission' => $objNew ['Userrelationpermission'] ['id'],
				'userrelationpermission' => json_encode($objNew ['Userrelationpermission']) 
		);
		$post1= $this->testAction('userrelationpermission/edit', array (
				'data' => $data,
				'return' => 'view',
				'method' => 'POST' 
		));
		
		$this->assertEquals(! empty($post1), 1);
		$this->assertEquals($this->controller->response->header('_headers', '') ['statuscod'], 1);
		$this->assertEquals($this->controller->response->header('_headers', '') ['responsecod'], 200);
		
		// test
		$bs= new UserrelationpermissionBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Userrelationpermission'] ['cod'], 'AltramioCodTest');
		
		// reset
		$ui= new UserrelationpermissionUI();
		$id= $ui->edit($id, $obj);
		
		// test
		$bs= new UserrelationpermissionBS();
		$search= $bs->unique(1);
		$this->assertEquals(! empty($search), true);
		$this->assertEquals($search ['Userrelationpermission'] ['cod'] == 'AltramioCodTest', false);
		$this->removeRecord($autoIncrement);
	}

	function testDelete() {
		/** @var \Cake\Model\Datasource\DboSource */
		$dbo= ConnectionManager::getDataSource("default");
		$autoIncrement= MysqlUtilityTest::getAutoIncrement($dbo, "userrelationpermissions");
		
		// insert
		$sql= "INSERT INTO userrelationpermissions (id,cod,created) VALUES";
		$sql.= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
		$data= $dbo->query($sql);
		
		// search
		$search= "SELECT * FROM userrelationpermissions WHERE cod='mioCodTest'";
		$data= $dbo->query($search);
		$result= $data [0] ['userrelationpermissions'];
		$id= $result ['id'];
		$this->assertEquals(! empty($data), true);
		$this->assertEquals($result ['cod'], 'mioCodTest');
		
		// delete
		$data= array (
				'id_userrelationpermission' => $id 
		);
		$post1= $this->testAction('userrelationpermission/delete', array (
				'data' => $data,
				'return' => 'view',
				'method' => 'POST' 
		));
		
		$this->assertEquals(! empty($post1), 1);
		$this->assertEquals($this->controller->response->header('_headers', '') ['statuscod'], 1);
		$this->assertEquals($this->controller->response->header('_headers', '') ['responsecod'], 200);
		
		// reset
		MysqlUtilityTest::resetAutoIncrement($dbo, $this, "userrelationpermissions", $autoIncrement);
		
		// verify reset
		MysqlUtilityTest::verifyDeleted($dbo, $this, "userrelationpermissions", "cod='mioCodTest'");
	}
}