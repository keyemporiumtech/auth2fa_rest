<?php
App::uses("AppModel", "Model");

/**
 * Entity Professionrole
 * 
 * @author Giuseppe Sassone
 *
 */
class Professionrole extends AppModel {
	public $arrayBelongsTo= array (
			'profession_fk' => array (
					'className' => 'Profession',
					'foreignKey' => 'profession'
			),
			'workrole_fk' => array (
					'className' => 'Workrole',
					'foreignKey' => 'role'
			)
	);
}
