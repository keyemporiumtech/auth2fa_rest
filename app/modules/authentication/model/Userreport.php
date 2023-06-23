<?php
App::uses("AppModel", "Model");

/**
 * Entity Userreport
 * 
 * @author Giuseppe Sassone
 *
 */
class Userreport extends AppModel {
	public $arrayBelongsTo= array (
			'user_fk' => array (
					'className' => 'User',
					'foreignKey' => 'user' 
			) 
	);
}
