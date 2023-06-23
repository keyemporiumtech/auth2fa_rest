<?php
App::uses("AppModel", "Model");

/**
 * Entity Testtypological
 * 
 * @author Giuseppe Sassone
 *
 */
class Testtypological extends AppModel {

	public function afterFind($results, $primary= false) {
		parent::translateValueInField($results, "cod", "title", "testtp");
		return parent::afterFind($results, $primary);
	}
}
