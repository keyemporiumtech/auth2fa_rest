<?php
App::uses("AppModel", "Model");
App::uses("SmsUtility", "modules/communication/utility");

/**
 * Entity Phone
 * 
 * @author Giuseppe Sassone
 *
 */
class Phone extends AppModel {

	public function afterFind($results, $primary= false) {
		foreach ( $results as &$obj ) {
			if (array_key_exists($this->alias, $obj)) {
				$obj [$this->alias] ['senderphone']= SmsUtility::evalNumberWithPlus($obj [$this->alias] ['senderphone']);
			}
		}
		return parent::afterFind($results);
	}

	public function beforeSave($options= array()) {
		if (! empty($this->data [$this->alias] ['senderphone'])) {
			$this->data [$this->alias] ['senderphone']= SmsUtility::evalNumberWithPlus($this->data [$this->alias] ['senderphone']);
		}
		return parent::beforeSave($options);
	}
}
