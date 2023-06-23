<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Language", "Model");

class LanguageBS extends AppGenericBS {

	function __construct() {
		parent::__construct('Language');
	}

	function used() {
		parent::addCondition($this->dao->alias . '.flgused', 1);
		parent::addOrder($this->dao->alias . '.cod');
		return $this->all();
	}

	function get($cod) {
		//parent::addCondition('flgused', 1);
		parent::addCondition($this->dao->alias . '.cod', $cod);
		return $this->unique();
	}
}