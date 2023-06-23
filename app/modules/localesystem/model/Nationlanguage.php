<?php
App::uses("AppModel", "Model");

class Nationlanguage extends AppModel {
	public $onlyused= true;
	public $arrayBelongsTo= array (
			'nation_fk' => array (
					'className' => 'Nation',
					'foreignKey' => 'nation' 
			),
			'language_fk' => array (
					'className' => 'Language',
					'foreignKey' => 'languageid' 
			) 
	);

	public function beforeFind($query) {
		parent::beforeFind($query);
		if (! $this->onlyused) {
			$query ['conditions'] [$this->alias . '.flgused']= "1";
		}
		return $query;
	}
}
