<?php
App::uses("AppModel", "Model");

/**
 * Entity Workactivity
 * 
 * @author Giuseppe Sassone
 *
 */
class Workactivity extends AppModel {
	public $arrayBelongsTo= array (
			'activity_fk' => array (
					'className' => 'Activity',
					'foreignKey' => 'activity' 
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
