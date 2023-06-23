<?php
App::uses("ArrayUtility", "modules/coreutils/utility");

class ArrayCompareTest extends CakeTestCase {
	public $list;
	public $listOther;
	public $arrayValues;
	public $arrayMulti;
	public $arrayMultiOtherKey;
	
	public $compare1;
	public $compare2;
	public $compare3;

	public function setUp() {
		parent::setUp();
		
		$this->compare1 = array(
				"key1" => 1,
				"key2" => 2,
				"key3" => 3,
		);
		$this->compare2 = array(
				"key1" => 0,
				"key2" => 1,
				"key3" => 2,
		);
		$this->compare3 = array(
				"key1" => 0,
				"key2" => 1,				
		);
		$this->arrayMultiOtherKey= array (
				"keyA" => array (
						"id" => 3,
						"title" => "terzo"
				),
				"keyB" => array (
						"id" => 1,
						"title" => "primo"
				),
				"keyC" => array (
						"id" => 2,
						"title" => "secondo"
				)
		);
		
		
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
		$this->listOther= array (
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
				),
				(int) 2 => array (
						'Test' => array (
								'id' => '3',
								'cod' => 'ENTITY003',
								'title' => 'TERZA ENTITY',
								'description' => 'RIGA DA VERIFICARE AL TERZO TEST ENTITY',
								'result' => '1',
								'created' => '2020-09-08 17:27:55',
								'modified' => null 
						) 
				) 
		);
		$this->arrayValues= array (
				1,
				2 
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
		
	}

	function testCompareArray() {
		$result1= ArrayUtility::compareArray($this->compare3, $this->compare2);
		$result2= ArrayUtility::compareArray($this->compare2, $this->compare3);
		$this->assertEquals($result1, - 1);
		$this->assertEquals($result2, 1);
		// equals
		$clone= $this->compare2;
		$result3= ArrayUtility::compareArray($this->compare2, $clone);
		$this->assertEquals($result3, 0);
		// uncomparable
		$result4= ArrayUtility::compareArray($this->compare2, $this->arrayMultiOtherKey);
		$this->assertEquals($result4, null);
		// stesse chiavi ma valori diversi	
		$result5= ArrayUtility::compareArray($this->compare2, $this->compare1);
		$result6= ArrayUtility::compareArray($this->compare1, $this->compare2);
		$this->assertEquals($result1, - 1);
		$this->assertEquals($result2, 1);
	}

	function testCompareArrayKey() {
		// MODEL
		$result1= ArrayUtility::compareArrayKey($this->list, $this->listOther, "Test");
		$result2= ArrayUtility::compareArrayKey($this->listOther, $this->list, "Test");
		$this->assertEquals($result1, - 1);
		$this->assertEquals($result2, 1);
		// equals
		$clone= $this->listOther;
		$result3= ArrayUtility::compareArrayKey($this->listOther, $clone, "Test");
		$this->assertEquals($result3, 0);
		// uncomparable chiavi diverse
		$result4= ArrayUtility::compareArrayKey($this->listOther, $this->arrayMultiOtherKey, "Test");
		$this->assertEquals($result4, null);
		// uncomparable chiave non presente
		$result5= ArrayUtility::compareArrayKey($this->listOther, $this->list, "Test", "inesistente");
		$this->assertEquals($result5, null);
	}

	function testCompareArrayKeyByListValue() {
		$result1= ArrayUtility::compareArrayKeyByListValue($this->arrayValues, $this->listOther, "Test");
		$this->assertEquals($result1, - 1);
		// aggiungo elementi all'array di valori
		$addrecords= $this->arrayValues;
		array_push($addrecords, 3);
		array_push($addrecords, 4);
		$result2= ArrayUtility::compareArrayKeyByListValue($addrecords, $this->listOther, "Test");
		$this->assertEquals($result2, 1);
		// equals
		$result3= ArrayUtility::compareArrayKeyByListValue($this->arrayValues, $this->list, "Test");
		$this->assertEquals($result3, 0);
		// uncomparable classe non presente
		$result4= ArrayUtility::compareArrayKeyByListValue($this->arrayValues, $this->arrayMulti, "Test");
		$this->assertEquals($result4, null);
		// uncomparable chiave non presente
		$result5= ArrayUtility::compareArrayKeyByListValue($this->arrayValues, $this->listOther, "Test", "inesistente");
		$this->assertEquals($result5, null);
	}
}
