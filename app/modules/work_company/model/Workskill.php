<?php
App::uses("AppModel", "Model");

/**
 * Entity Workskill
 * 
 * @author Giuseppe Sassone
 *
 */
class Workskill extends AppModel {
	public $arrayBelongsTo= array (
			'tpskill_fk' => array (
					'className' => 'Tpskill',
					'foreignKey' => 'tpskill' 
			) 
	);
}
