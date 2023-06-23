<?php
App::uses("AppModel", "Model");

/**
 * Entity Categoryattachment
 * 
 * @author Giuseppe Sassone
 *
 */
class Categoryattachment extends AppModel {
	public $arrayBelongsTo= array (
			'category_fk' => array (
					'className' => 'Category',
					'foreignKey' => 'category' 
			),
			'attachment_fk' => array (
					'className' => 'Attachment',
					'foreignKey' => 'attachment' 
			) 
	);
}
