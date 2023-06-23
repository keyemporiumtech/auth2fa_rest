<?php
App::uses("AppModel", "Model");

/**
 * Entity Productdiscount
 * 
 * @author Giuseppe Sassone
 *
 */
class Productdiscount extends AppModel {
	public $arrayBelongsTo= array (
			'product_fk' => array (
					'className' => 'Product',
					'foreignKey' => 'product' 
			),
			'discount_fk' => array (
					'className' => 'Discount',
					'foreignKey' => 'discount' 
			) 
	);
}
