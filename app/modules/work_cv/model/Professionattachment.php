<?php
App::uses("AppModel", "Model");

/**
 * Entity Professionattachment
 * 
 * @author Giuseppe Sassone
 *
 */
class Professionattachment extends AppModel {
	public $arrayBelongsTo= array (
			'profession_fk' => array (
					'className' => 'Profession',
					'foreignKey' => 'profession' 
			),
			'attachment_fk' => array (
					'className' => 'Attachment',
					'foreignKey' => 'attachment' 
			) 
	);
}
