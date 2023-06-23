<?php
App::uses("AppModel", "Model");

/**
 * Entity Product
 * 
 * @author Giuseppe Sassone
 *
 */
class Product extends AppModel {
	public $arrayBelongsTo= array (
			'image_fk' => array (
					'className' => 'Attachment',
					'foreignKey' => 'image'
			),
			'brand_fk' => array (
					'className' => 'Brand',
					'foreignKey' => 'brand'
			),
			'category_fk' => array (
					'className' => 'Category',
					'foreignKey' => 'category'
			),
			'price_fk' => array (
					'className' => 'Price',
					'foreignKey' => 'price'
			)
	);
}
