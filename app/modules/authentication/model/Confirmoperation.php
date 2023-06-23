<?php
App::uses("AppModel", "Model");

/**
 * Entity Confirmoperation
 * 
 * @author Giuseppe Sassone
 *
 */
class Confirmoperation extends AppModel {
	public $arrayBelongsTo= array (
			'user_fk' => array (
					'className' => 'User',
					'foreignKey' => 'user' 
			) 
	);
}
