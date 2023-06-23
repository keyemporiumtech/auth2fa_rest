<?php
App::uses("AppModel", "Model");

/**
 * Entity Workuser
 * 
 * @author Giuseppe Sassone
 *
 */
class Workuser extends AppModel {
	public $arrayBelongsTo= array (
			'user_fk' => array (
					'className' => 'User',
					'foreignKey' => 'user' 
			),
			'image_fk' => array (
					'className' => 'Attachment',
					'foreignKey' => 'image' 
			),
			'home_fk' => array (
					'className' => 'Address',
					'foreignKey' => 'home' 
			),
			'email_fk' => array (
					'className' => 'Contactrefence',
					'foreignKey' => 'email' 
			),
			'phone_fk' => array (
					'className' => 'Contactrefence',
					'foreignKey' => 'phone' 
			),
			'website_fk' => array (
					'className' => 'Contactrefence',
					'foreignKey' => 'website' 
			) 
	);
}
