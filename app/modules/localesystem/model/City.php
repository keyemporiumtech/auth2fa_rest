<?php
App::uses("AppModel", "Model");
/**
 * Entity City
 * 
 * @author Giuseppe Sassone
 *
 */
class City extends AppModel {
	public $arrayBelongsTo= array (
			'nation_fk' => array (
					'className' => 'Nation',
					'foreignKey' => 'nation' 
			) 
	);
	public $arrayVirtualFields= array (
			'nation_val' => "SELECT name FROM nations as Nation WHERE Nation.id = City.nation",
			'nation_cod' => "SELECT cod_iso3166 FROM nations as Nation WHERE Nation.id = City.nation",
			'filter_search' => "SELECT CONCAT(postalcode, ' ', place, ' ', community, ' ')" 
	);
}
