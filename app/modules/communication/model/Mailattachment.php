<?php
App::uses("AppModel", "Model");

/**
 * Entity Mailattachment
 * 
 * @author Giuseppe Sassone
 *
 */
class Mailattachment extends AppModel {
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
