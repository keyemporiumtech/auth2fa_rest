<?php
App::uses("AppModel", "Model");
App::uses("SmsUtility", "modules/communication/utility");

/**
 * Entity Phonereceiver
 * 
 * @author Giuseppe Sassone
 *
 */
class Phonereceiver extends AppModel {
	public $arrayBelongsTo= array (
			'phone_fk' => array (
					'className' => 'Phone',
					'foreignKey' => 'phone' 
			) 
	);

	public function afterFind($results, $primary= false) {
		foreach ( $results as &$obj ) {
			if (array_key_exists($this->alias, $obj)) {
				$obj [$this->alias] ['receiverphone']= SmsUtility::evalNumberWithPlus($obj [$this->alias] ['receiverphone']);
			}
		}
		return parent::afterFind($results);
	}

	public function beforeSave($options= array()) {
		if (! empty($this->data [$this->alias] ['receiverphone'])) {
			$this->data [$this->alias] ['receiverphone']= SmsUtility::evalNumberWithPlus($this->data [$this->alias] ['receiverphone']);
		}
		return parent::beforeSave($options);
	}
}
