<?php
App::uses("AppModel", "Model");

/**
 * Entity Brand
 * 
 * @author Giuseppe Sassone
 *
 */
class Brand extends AppModel {
	public $arrayBelongsTo= array (
			'image_fk' => array (
					'className' => 'Attachment',
					'foreignKey' => 'image' 
			) 
	);
}
