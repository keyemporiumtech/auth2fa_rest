<?php
App::uses('AppModel', 'Model');

class Testfk extends AppModel {
	public $prop1;
	public $prop2;
	public $arrayBelongsTo= array (
			'test_fk' => array (
					'className' => 'Test',
					'foreignKey' => 'test' 
			) 
	);
	public $arrayVirtualFields= array (
			'test_title' => "SELECT title FROM tests as Test WHERE Test.id = Testfk.test" 
	);
}