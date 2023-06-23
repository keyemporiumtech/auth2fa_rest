<?php
App::uses("AppModel", "Model");

/**
 * Entity Pocketproduct
 * 
 * @author Giuseppe Sassone
 *
 */
class Pocketproduct extends AppModel {
	public $arrayBelongsTo= array (
			'pocket_fk' => array (
					'className' => 'Pocket',
					'foreignKey' => 'pocket' 
			),
			'product_fk' => array (
					'className' => 'Product',
					'foreignKey' => 'product' 
			) 
	);
}
