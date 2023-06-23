<?php
App::uses("AppModel", "Model");

/**
 * Entity Pocketservice
 * 
 * @author Giuseppe Sassone
 *
 */
class Pocketservice extends AppModel {
	public $arrayBelongsTo= array (
			'pocket_fk' => array (
					'className' => 'Pocket',
					'foreignKey' => 'pocket' 
			),
			'service_fk' => array (
					'className' => 'Service',
					'foreignKey' => 'service' 
			) 
	);
}
