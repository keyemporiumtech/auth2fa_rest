<?php
App::uses("AppModel", "Model");

/**
 * Entity Service
 * 
 * @author Giuseppe Sassone
 *
 */
class Service extends AppModel {
	public $arrayBelongsTo= array (
			'image_fk' => array (
					'className' => 'Attachment',
					'foreignKey' => 'image'
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
