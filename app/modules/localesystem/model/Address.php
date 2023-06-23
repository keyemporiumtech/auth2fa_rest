<?php
App::uses("AppModel", "Model");
App::uses('City', 'Model');

/**
 * Entity Address
 * 
 * @author Giuseppe Sassone
 *
 */
class Address extends AppModel {
	public $arrayBelongsTo= array (
			'city_fk' => array (
					'className' => 'City',
					'foreignKey' => 'cityid' 
			),
			'nation_fk' => array (
					'className' => 'Nation',
					'foreignKey' => 'nation' 
			),
			'tpaddress_fk' => array (
					'className' => 'Tpaddress',
					'foreignKey' => 'tpaddress' 
			) 
	);
	public $arrayVirtualFields= array (
			'nation_val' => "SELECT name FROM nations as Nation WHERE Nation.id = Address.nation",
			'nation_cod' => "SELECT cod_iso3166 FROM nations as Nation WHERE Nation.id = Address.nation",
			'city_val' => "SELECT place FROM cities as City WHERE City.id = Address.cityid" 
	);

	public function afterFind($results, $primary= false) {
		$this->setAddressMapping($results);
		return parent::afterFind($results, $primary);
	}

	function setAddressMapping(&$data) {
		foreach ( $data as &$obj ) {
			$cityModel= null;
			if (array_key_exists($this->alias, $obj) && ! empty($obj [$this->alias] ['cityid'])) {
				$city= new City();
				$cityModel= $city->find('first', array (
						'conditions' => array (
								'id' => $obj [$this->alias] ['cityid'] 
						) 
				));
			} elseif (array_key_exists($this->alias, $obj) && ! empty($obj [$this->alias] ['city']) && ! empty($obj [$this->alias] ['zip'])) {
				$city= new City();
				$cityModel= $city->find('first', array (
						'conditions' => array (
								'place' => $obj [$this->alias] ['city'],
								'postalcode' => $obj [$this->alias] ['zip'] 
						) 
				));
			}
			if (! empty($cityModel)) {
				if (array_key_exists("city_fk", $this->belongsTo)) {
					unset($this->belongsTo['city_fk']);
					$obj [$this->alias] ["city_fk"]= $cityModel['City'];					
				}
				if (empty($obj [$this->alias] ['cityid'])) {
					$obj [$this->alias] ['cityid']= $cityModel ['City'] ['id'];
				}
				if (empty($obj [$this->alias] ['city'])) {
					$obj [$this->alias] ['city']= $cityModel ['City'] ['place'];
				}
				if (empty($obj [$this->alias] ['province'])) {
					$obj [$this->alias] ['province']= $cityModel ['City'] ['province'];
					/*
					 * if (! empty($cityModel ['City'] ['provincecode'])) {
					 * $obj [$this->alias] ['province'].= "(" . $cityModel ['City'] ['provincecode'] . ")";
					 * }
					 */
				}
				if (empty($obj [$this->alias] ['zip'])) {
					$obj [$this->alias] ['zip']= $cityModel ['City'] ['postalcode'];
				}
				if (empty($obj [$this->alias] ['region'])) {
					$obj [$this->alias] ['region']= $cityModel ['City'] ['region'];
					/*
					 * if (! empty($cityModel ['City'] ['regioncode'])) {
					 * $obj [$this->alias] ['region'].= "(" . $cityModel ['City'] ['regioncode'] . ")";
					 * }
					 */
				}
				if (empty($obj [$this->alias] ['nation'])) {
					$obj [$this->alias] ['nation']= $cityModel ['City'] ['nation'];
				}
				if (empty($obj [$this->alias] ['geo1'])) {
					$obj [$this->alias] ['geo1']= $cityModel ['City'] ['geo1'];
				}
				if (empty($obj [$this->alias] ['geo2'])) {
					$obj [$this->alias] ['geo2']= $cityModel ['City'] ['geo2'];
				}
			}
		}
	}
}
