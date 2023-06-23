<?php
App::uses("AppModel", "Model");

/**
 * Entity Brandattachment
 * 
 * @author Giuseppe Sassone
 *
 */
class Brandattachment extends AppModel {
	public $arrayBelongsTo= array (
			'brand_fk' => array (
					'className' => 'Brand',
					'foreignKey' => 'brand' 
			),
			'attachment_fk' => array (
					'className' => 'Attachment',
					'foreignKey' => 'attachment' 
			) 
	);
}
