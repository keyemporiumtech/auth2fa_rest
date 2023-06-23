<?php
App::uses("AppModel", "Model");

/**
 * Entity Pocket
 * 
 * @author Giuseppe Sassone
 *
 */
class Pocket extends AppModel {
	public $arrayBelongsTo= array (
			'image_fk' => array (
					'className' => 'Attachment',
					'foreignKey' => 'image' 
			),
			'price_fk' => array (
					'className' => 'Price',
					'foreignKey' => 'price' 
			) 
	);
}
