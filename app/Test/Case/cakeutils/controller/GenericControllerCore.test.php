<?php

class GenericControllerCoreTest extends ControllerTestCase {

	function testGet() {
		$data= array (
				'id' => 1 
		);
		$post1= $this->testAction('testfk/get', array (
				'data' => $data,
				'return' => 'view',
				'method' => 'POST' 
		));
		$responseData= json_decode($post1, true);
		$this->assertEquals($responseData ['id'], 1);
		$this->assertEquals($this->controller->response->header('_headers', '') ['statuscod'], 1);
		$this->assertEquals($this->controller->response->header('_headers', '') ['responsecod'], 200);
		
		$data= array (
				'id' => 4 
		);
		$post1= $this->testAction('testfk/get', array (
				'data' => $data,
				'return' => 'view',
				'method' => 'POST' 
		));
		$responseData= json_decode($post1, true);
		$this->assertEquals($responseData, null);
		$this->assertEquals($this->controller->response->header('_headers', '') ['statuscod'], - 1);
		$this->assertEquals($this->controller->response->header('_headers', '') ['responsecod'], 200);
		$this->assertEquals($this->controller->response->header('_headers', '') ['exceptioncod'], 500);
	}

	function testTable() {
		$data= array ();
		$post1= $this->testAction('testfk/table', array (
				'data' => $data,
				'return' => 'view',
				'method' => 'POST' 
		));
		$paginator= json_decode($post1, true);
		$this->assertEquals($paginator ['list'] [0] ['id'], 1);
		$this->assertEquals($paginator ['count'], 0);
		$this->assertEquals($paginator ['pages'], 0);
		$this->assertEquals($this->controller->response->header('_headers', '') ['statuscod'], 1);
		$this->assertEquals($this->controller->response->header('_headers', '') ['responsecod'], 200);
		
		// CONDITIONS
		$condition= new DBCondition();
		$condition->key= "id";
		$condition->value= 4;
		$conditions= array (
				$condition 
		);
		
		$data= array (
				'filters' => json_encode($conditions) 
		);
		$post2= $this->testAction('testfk/table', array (
				'data' => $data,
				'return' => 'view',
				'method' => 'POST' 
		));
		
		$paginator= json_decode($post2, true);
		$this->assertEquals(count($paginator ['list']), 0);
		$this->assertEquals($paginator ['count'], 0);
		$this->assertEquals($paginator ['pages'], 0);
	}

	function testPaginate() {
		$paginate= new DBPaginate();
		$paginate->limit= 1;
		$paginate->page= 2;
		
		$data= array (
				'paginate' => json_encode($paginate) 
		);
		$post1= $this->testAction('testfk/table', array (
				'data' => $data,
				'return' => 'view',
				'method' => 'POST' 
		));
		
		$paginator= json_decode($post1, true);
		$this->assertEquals($paginator ['list'] [0] ['id'], 2);
		$this->assertEquals(count($paginator ['list']), 1);
		$this->assertEquals($paginator ['count'], 2);
		$this->assertEquals($paginator ['pages'], 2);
	}
}