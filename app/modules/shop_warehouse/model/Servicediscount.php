<?php
App::uses("AppModel", "Model");

/**
 * Entity Servicediscount
 * 
 * @author Giuseppe Sassone
 *
 */
class Servicediscount extends AppModel {
	public $arrayBelongsTo= array (
			'service_fk' => array (
					'className' => 'Service',
					'foreignKey' => 'service' 
			),
			'discount_fk' => array (
					'className' => 'Discount',
					'foreignKey' => 'discount' 
			) 
	);
}
