<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Nation", "Model");

class NationBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Nation');
	}

	function used() {
		parent::addCondition($this->dao->alias . '.flgused', 1);
		parent::addOrder($this->dao->alias . '.cod');
		return $this->all();
	}

	function getByCod($cod) {
		// parent::addCondition($this->dao->alias .'.flgused', 1);
		parent::addCondition($this->dao->alias . '.cod', $cod);
		return $this->unique();
	}

	function getByIso($cod) {
		// parent::addCondition('flgused', 1);
		parent::addCondition($this->dao->alias . '.cod_iso3166', $cod);
		return $this->unique();
	}
}