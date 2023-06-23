<?php
App::uses("AppModel", "Model");

/**
 * Entity Mailreceiver
 * 
 * @author Giuseppe Sassone
 *
 */
class Mailreceiver extends AppModel {
	public $arrayBelongsTo= array (
			'mail_fk' => array (
					'className' => 'Mail',
					'foreignKey' => 'mail' 
			) 
	);
}
