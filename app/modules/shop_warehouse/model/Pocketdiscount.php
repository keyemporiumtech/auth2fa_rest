<?php
App::uses("AppModel", "Model");

/**
 * Entity Pocketdiscount
 * 
 * @author Giuseppe Sassone
 *
 */
class Pocketdiscount extends AppModel {
	public $arrayBelongsTo= array (
			'pocket_fk' => array (
					'className' => 'Pocket',
					'foreignKey' => 'pocket' 
			),
			'discount_fk' => array (
					'className' => 'Discount',
					'foreignKey' => 'discount' 
			) 
	);
}
