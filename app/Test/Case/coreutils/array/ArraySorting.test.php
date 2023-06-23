<?php
App::uses("ArrayUtility", "modules/coreutils/utility");

class ArraySortingTest extends CakeTestCase {
	public $list;
	public $arrayMulti;
	public $arrayObjects;

	public function setUp() {
		parent::setUp();
		$this->list= array (
				(int) 0 => array (
						'Test' => array (
								'id' => '1',
								'cod' => 'ENTITY001',
								'title' => 'PRIMA ENTITY',
								'description' => 'RIGA DA VERIFICARE AL PRIMO TEST ENTITY',
								'result' => '1',
								'created' => '2020-09-08 17:27:55',
								'modified' => null 
						) 
				),
				(int) 1 => array (
						'Test' => array (
								'id' => '2',
								'cod' => 'ENTITY002',
								'title' => 'SECONDA ENTITY',
								'description' => 'RIGA DA VERIFICARE AL SECONDO TEST ENTITY',
								'result' => '1',
								'created' => '2020-09-08 17:27:55',
								'modified' => null 
						) 
				) 
		);
		$this->arrayMulti= array (
				array (
						"id" => 3,
						"title" => "terzo" 
				),
				array (
						"id" => 1,
						"title" => "primo" 
				),
				array (
						"id" => 2,
						"title" => "secondo" 
				) 
		);
		$this->arrayObjects= array (
				0 => new TestSortingClass(3, "terzo"),
				1 => new TestSortingClass(1, "primo"),
				2 => new TestSortingClass(2, "secondo") 
		);
	}

	function testSortArrayByKey() {
		$result1= ArrayUtility::sortArrayByKey($this->arrayMulti, "id");
		$result2= ArrayUtility::sortArrayByKey($this->arrayMulti, "id", true);
		$this->assertEquals($result1 [0] ['id'], 1);
		$this->assertEquals($result1 [1] ['id'], 2);
		$this->assertEquals($result1 [2] ['id'], 3);
		$this->assertEquals($result2 [0] ['id'], 3);
		$this->assertEquals($result2 [1] ['id'], 2);
		$this->assertEquals($result2 [2] ['id'], 1);
	}

	function testSortArrayByKeyWithCakeModel() {
		$result1= ArrayUtility::sortArrayByKey($this->list, "id");
		$result2= ArrayUtility::sortArrayByKey($this->list, "id", true);
		$this->assertEquals($result1 [0] ['Test'] ['id'], 1);
		$this->assertEquals($result1 [1] ['Test'] ['id'], 2);
		$this->assertEquals($result2 [0] ['Test'] ['id'], 2);
		$this->assertEquals($result2 [1] ['Test'] ['id'], 1);
	}

	function testSortObjectsByField() {
		$result1= ArrayUtility::sortObjectsByField($this->arrayObjects, "id");
		$result2= ArrayUtility::sortObjectsByField($this->arrayObjects, "id", true);
		$this->assertEquals($result1 [0]->id, 1);
		$this->assertEquals($result1 [1]->id, 2);
		$this->assertEquals($result1 [2]->id, 3);
		$this->assertEquals($result2 [0]->id, 3);
		$this->assertEquals($result2 [1]->id, 2);
		$this->assertEquals($result2 [2]->id, 1);
	}

	function testSortObjects() {
		$result1= ArrayUtility::sortObjects($this->arrayObjects, "TestSortingClass", "sortAscById", "sortDescById");
		$result2= ArrayUtility::sortObjects($this->arrayObjects, "TestSortingClass", "sortAscById", "sortDescById", false);
		$this->assertEquals($result1 [0]->id, 1);
		$this->assertEquals($result1 [1]->id, 2);
		$this->assertEquals($result1 [2]->id, 3);
		$this->assertEquals($result2 [0]->id, 3);
		$this->assertEquals($result2 [1]->id, 2);
		$this->assertEquals($result2 [2]->id, 1);
	}
}

class TestSortingClass {
	public $id;
	public $title;

	function __construct($id, $title) {
		$this->id= $id;
		$this->title= $title;
	}

	static function sortAscById($a, $b) {
		return strcmp($a->id, $b->id);
	}

	static function sortDescById($a, $b) {
		return strcmp($b->id, $a->id);
	}
}