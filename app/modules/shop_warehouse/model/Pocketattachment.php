<?php
App::uses("AppModel", "Model");

/**
 * Entity Pocketattachment
 * 
 * @author Giuseppe Sassone
 *
 */
class Pocketattachment extends AppModel {
	public $arrayBelongsTo= array (
			'pocket_fk' => array (
					'className' => 'Pocket',
					'foreignKey' => 'pocket' 
			),
			'attachment_fk' => array (
					'className' => 'Attachment',
					'foreignKey' => 'attachment' 
			) 
	);
}
