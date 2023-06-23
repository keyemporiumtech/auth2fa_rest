<?php
App::uses("AppModel", "Model");

/**
 * Entity Professionexperience
 * 
 * @author Giuseppe Sassone
 *
 */
class Professionexperience extends AppModel {
	public $arrayBelongsTo= array (
			'profession_fk' => array (
					'className' => 'Profession',
					'foreignKey' => 'profession' 
			),
			'workexperience_fk' => array (
					'className' => 'Workexperience',
					'foreignKey' => 'experience' 
			) 
	);
}
