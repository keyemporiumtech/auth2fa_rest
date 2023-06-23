<?php
App::uses("AppModel", "Model");

/**
 * Entity Workexperiencerole
 * 
 * @author Giuseppe Sassone
 *
 */
class Workexperiencerole extends AppModel {
	public $arrayBelongsTo= array (
			'workexperience_fk' => array (
					'className' => 'Workexperience',
					'foreignKey' => 'experience' 
			),
			'workrole_fk' => array (
					'className' => 'Workrole',
					'foreignKey' => 'role' 
			) 
	);
}
