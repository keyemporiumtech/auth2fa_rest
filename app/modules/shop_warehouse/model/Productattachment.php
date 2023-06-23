<?php
App::uses("AppModel", "Model");

/**
 * Entity Productattachment
 * 
 * @author Giuseppe Sassone
 *
 */
class Productattachment extends AppModel {
	public $arrayBelongsTo= array (
			'product_fk' => array (
					'className' => 'Product',
					'foreignKey' => 'product' 
			),
			'attachment_fk' => array (
					'className' => 'Attachment',
					'foreignKey' => 'attachment' 
			) 
	);
}
