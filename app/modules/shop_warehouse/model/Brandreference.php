<?php
App::uses("AppModel", "Model");

/**
 * Entity Brandreference
 * 
 * @author Giuseppe Sassone
 *
 */
class Brandreference extends AppModel {
	public $arrayBelongsTo= array (
			'brand_fk' => array (
					'className' => 'Brand',
					'foreignKey' => 'brand'
			),
			'contactreference_fk' => array (
					'className' => 'Contactreference',
					'foreignKey' => 'contactreference'
			)
	);
}
