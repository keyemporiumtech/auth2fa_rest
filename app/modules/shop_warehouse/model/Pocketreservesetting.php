<?php
App::uses("AppModel", "Model");

/**
 * Entity Pocketreservesetting
 * 
 * @author Giuseppe Sassone
 *
 */
class Pocketreservesetting extends AppModel {
	public $arrayBelongsTo= array (
			'pocket_fk' => array (
					'className' => 'Pocket',
					'foreignKey' => 'pocket' 
			),
			'reservationsetting_fk' => array (
					'className' => 'Reservesetting',
					'foreignKey' => 'settings' 
			) 
	);
}
