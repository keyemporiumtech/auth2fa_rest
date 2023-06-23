<?php
App::uses("AppModel", "Model");

/**
 * Entity Serviceattachment
 * 
 * @author Giuseppe Sassone
 *
 */
class Serviceattachment extends AppModel {
	public $arrayBelongsTo= array (
			'service_fk' => array (
					'className' => 'Service',
					'foreignKey' => 'service' 
			),
			'attachment_fk' => array (
					'className' => 'Attachment',
					'foreignKey' => 'attachment' 
			) 
	);
}
