<?php
App::uses("AppModel", "Model");

/**
 * Entity Professionskill
 * 
 * @author Giuseppe Sassone
 *
 */
class Professionskill extends AppModel {
	public $arrayBelongsTo= array (
			'profession_fk' => array (
					'className' => 'Profession',
					'foreignKey' => 'profession'
			),
			'workskill_fk' => array (
					'className' => 'Workskill',
					'foreignKey' => 'skill'
			)
	);
}
