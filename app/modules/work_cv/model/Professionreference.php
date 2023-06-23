<?php
App::uses("AppModel", "Model");

/**
 * Entity Professionreference
 * 
 * @author Giuseppe Sassone
 *
 */
class Professionreference extends AppModel {
	public $arrayBelongsTo= array (
			'profession_fk' => array (
					'className' => 'Profession',
					'foreignKey' => 'profession' 
			),
			'contactreference_fk' => array (
					'className' => 'Contactreference',
					'foreignKey' => 'contactreference' 
			) 
	);
}
