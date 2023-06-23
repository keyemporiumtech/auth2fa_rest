<?php
App::uses("AppModel", "Model");

/**
 * Entity Tpattachment
 * 
 * @author Giuseppe Sassone
 *
 */
class Tpattachment extends AppModel {
	public $onlyUsed= true;
	public $avoidContent= false;
	
	public function beforeFind($query) {
		parent::beforeFind($query);
		if (! $this->onlyUsed) {
			$query ['conditions'] [$this->alias . '.flgused']= "1";
		}
		return $query;
	}
	
	public function afterFind($results, $primary= false) {
		parent::translateValueInField($results, "cod", "title", "tpattachment");
		return parent::afterFind($results, $primary);
	}
}
