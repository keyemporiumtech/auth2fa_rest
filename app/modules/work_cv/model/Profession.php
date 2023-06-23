<?php
App::uses("AppModel", "Model");

/**
 * Entity Profession
 * 
 * @author Giuseppe Sassone
 *
 */
class Profession extends AppModel {
	public $arrayBelongsTo= array (
			'user_fk' => array (
					'className' => 'User',
					'foreignKey' => 'user'
			),
			'image_fk' => array (
					'className' => 'Attachment',
					'foreignKey' => 'image'
			),
			'address_fk' => array (
					'className' => 'Address',
					'foreignKey' => 'address'
			),
			'email_fk' => array (
					'className' => 'Contactrefence',
					'foreignKey' => 'email'
			),
			'phone_fk' => array (
					'className' => 'Contactrefence',
					'foreignKey' => 'phone'
			)
	);
}
