<?php
App::uses("AppModel", "Model");

/**
 * Entity Category
 * 
 * @author Giuseppe Sassone
 *
 */
class Category extends AppModel {
	public $arrayBelongsTo= array (
			'image_fk' => array (
					'className' => 'Attachment',
					'foreignKey' => 'image' 
			),
			'parent_fk' => array (
					'className' => 'Category',
					'foreignKey' => 'parent_id' 
			) 
	);
}
