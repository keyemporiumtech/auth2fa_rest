<?php
App::uses("AppModel", "Model");

/**
 * Entity Workexperiencecompany
 * 
 * @author Giuseppe Sassone
 *
 */
class Workexperiencecompany extends AppModel {
	public $arrayBelongsTo= array (
			'workexperience_fk' => array (
					'className' => 'Workexperience',
					'foreignKey' => 'experience' 
			),
			'activity_fk' => array (
					'className' => 'Activity',
					'foreignKey' => 'company' 
			) 
	);
}
