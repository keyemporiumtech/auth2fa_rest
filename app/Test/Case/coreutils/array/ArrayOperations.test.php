<?php
App::uses("ArrayUtility", "modules/coreutils/utility");

class ArrayOperationsTest extends CakeTestCase {
	public $list;
	public $array;

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
		$this->array= array (
				"id" => "1",
				"title" => "primo" 
		);
	}

	function testRemoveObjectByField() {
		$test= $this->list;
		ArrayUtility::removeObjectByField($test, "Test", "1");
		$this->assertEquals(count($test) > 0, true);
		$this->assertEquals($test [1] ['Test'] ["id"], "2");
	}

	function testGetArrayIdByObject() {
		$result= ArrayUtility::getArrayIdByObject($this->list, "Test");
		$this->assertEquals(count($result) > 0, true);
		$this->assertEquals($result [0], "1");
	}

	function testInvertKeyValue() {
		$result= ArrayUtility::invertKeyValue($this->array);
		$this->assertEquals(count($result) > 0, true);
		$this->assertEquals($result ["1"], "id");
		$this->assertEquals($result ["primo"], "title");
	}

	function testGetKeyByValue() {
		$result= ArrayUtility::getKeyByValue($this->array, "1");
		$this->assertEquals($result, "id");
	}

	function testIsEmpty() {
		$result1= ArrayUtility::isEmpty($this->array);
		$result2= ArrayUtility::isEmpty(array ());
		$result3= ArrayUtility::isEmpty(null);
		$this->assertEquals($result1, false);
		$this->assertEquals($result2, true);
		$this->assertEquals($result3, true);
	}

	function testIsMulti() {
		$result1= ArrayUtility::isMulti($this->list);
		$result2= ArrayUtility::isMulti($this->array);
		$this->assertEquals($result1, true);
		$this->assertEquals($result2, false);
	}
	
	function testReplaceKey() {
		$test = $this->array;
		ArrayUtility::replaceKey($test, "id", "cod");		
		$this->assertEquals(array_key_exists("id", $test), false);
		$this->assertEquals(array_key_exists("cod", $test), true);
		$this->assertEquals($test['cod'] == "1", true);
	}
}