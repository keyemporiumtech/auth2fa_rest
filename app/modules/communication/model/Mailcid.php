<?php
App::uses("AppModel", "Model");

/**
 * Entity Mailcid
 * 
 * @author Giuseppe Sassone
 *
 */
class Mailcid extends AppModel {
	public $arrayBelongsTo= array (
			'mail_fk' => array (
					'className' => 'Mail',
					'foreignKey' => 'mail' 
			),
			'attachment_fk' => array (
					'className' => 'Attachment',
					'foreignKey' => 'attachment' 
			) 
	);
}
