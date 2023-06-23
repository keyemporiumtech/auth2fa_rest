<?php
App::uses("AppModel", "Model");

/**
 * Entity Professionschool
 * 
 * @author Giuseppe Sassone
 *
 */
class Professionschool extends AppModel {
	public $arrayBelongsTo= array (
			'profession_fk' => array (
					'className' => 'Profession',
					'foreignKey' => 'profession' 
			),
			'activity_fk' => array (
					'className' => 'Activity',
					'foreignKey' => 'institute' 
			) 
	);
}
