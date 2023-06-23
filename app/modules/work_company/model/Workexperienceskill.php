<?php
App::uses("AppModel", "Model");

/**
 * Entity Workexperienceskill
 * 
 * @author Giuseppe Sassone
 *
 */
class Workexperienceskill extends AppModel {
	public $arrayBelongsTo= array (
			'workexperience_fk' => array (
					'className' => 'Workexperience',
					'foreignKey' => 'experience'
			),
			'workskill_fk' => array (
					'className' => 'Workskill',
					'foreignKey' => 'skill'
			)
	);
}
