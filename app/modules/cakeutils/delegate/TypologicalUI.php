<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("ConnectionManager", "Model");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("FileUtility", "modules/coreutils/utility");

class TypologicalUI extends AppGenericUI {
	public $bsName= null;

	function __construct($entityname, $moduleName) {
		parent::__construct("TypologicalUI");
		$this->localefile= "typological";
		$this->obj= array (
				new ObjPropertyEntity("cod", null, FileUtility::uuid_short()),
				new ObjPropertyEntity("title", null, ""),
				new ObjPropertyEntity("symbol", null, ""),
				new ObjPropertyEntity("flgused", null, 0) 
		);
		// manage type
		$this->bsName= ucfirst($entityname) . "BS";
		App::uses("{$this->bsName}", "modules/" . strtolower($moduleName) . "/business");
	}

	function get($id= null, $cod= null, $symbol= null) {
		$this->LOG_FUNCTION= "get";
		try {
			if (empty($id) && empty($cod) && empty($symbol)) {
				DelegateUtility::paramsNull($this, "ERROR_TYPOLOGICAL_NOT_FOUND");
				return "";
			}
			$typologicalBS= new $this->bsName();
			$typologicalBS->json= $this->json;
			parent::completeByJsonFkVf($typologicalBS);
			if (! empty($cod)) {
				$typologicalBS->addCondition("cod", $cod);
			}
			if (! empty($symbol)) {
				$typologicalBS->addCondition("symbol", $symbol);
			}
			$this->ok();
			return $typologicalBS->unique($id);
		} catch ( Exception $e ) {
			DelegateUtility::eccezione($e, $this, "ERROR_TYPOLOGICAL_NOT_FOUND");
			return "";
		}
	}

	function table($conditions= null, $orders= null, $paginate= null, $bs= null) {
		$this->LOG_FUNCTION= "table";
		try {
			$typologicalBS= ! empty($bs) ? $bs : new $this->bsName();
			$typologicalBS->json= $this->json;
			parent::completeByJsonFkVf($typologicalBS);
			parent::evalConditions($typologicalBS, $conditions);
			parent::evalOrders($typologicalBS, $orders);
			$typologicals= $typologicalBS->table($conditions, $orders, $paginate);
			parent::evalPagination($typologicalBS, $paginate);
			$this->ok();
			return parent::paginateForResponse($typologicals);
		} catch ( Exception $e ) {
			DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
			return "";
		}
	}

	function save($typologicalIn) {
		$this->LOG_FUNCTION= "save";
		try {
			parent::startTransaction();
			
			$typological= DelegateUtility::getEntityToSave(new $this->bsName(), $typologicalIn, $this->obj);
			
			if (! empty($typological)) {
				
				$typologicalBS= new $this->bsName();
				$id_typological= $typologicalBS->save($typological);
				
				parent::commitTransaction();
				if (! empty($id_typological)) {
					DelegateUtility::integratEntityCod(new $this->bsName(), $typological, $id_typological);
					$this->ok(TranslatorUtility::__translate("INFO_TYPOLOGICAL_SAVE", $this->localefile));
					return $id_typological;
				} else {
					parent::rollbackTransaction();
					DelegateUtility::nonSalvato($this, "ERROR_TYPOLOGICAL_SAVE");
					return 0;
				}
			} else {
				parent::rollbackTransaction();
				DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_TYPOLOGICAL_SAVE");
				return 0;
			}
		} catch ( Exception $e ) {
			parent::rollbackTransaction();
			DelegateUtility::eccezione($e, $this, "ERROR_TYPOLOGICAL_SAVE");
			return 0;
		}
	}

	function edit($id, $typologicalIn) {
		$this->LOG_FUNCTION= "edit";
		try {
			parent::startTransaction();
			
			$typological= DelegateUtility::getEntityToEdit(new $this->bsName(), $typologicalIn, $this->obj, $id);
			
			if (! empty($typological)) {
				$typologicalBS= new $this->bsName();
				$id_typological= $typologicalBS->save($typological);
				parent::commitTransaction();
				if (! empty($id_typological)) {
					$this->ok(TranslatorUtility::__translate("INFO_TYPOLOGICAL_EDIT", $this->localefile));
					return $id_typological;
				} else {
					parent::rollbackTransaction();
					DelegateUtility::nonModificato($this, "ERROR_TYPOLOGICAL_EDIT");
					return 0;
				}
			} else {
				parent::rollbackTransaction();
				DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_TYPOLOGICAL_EDIT");
				return 0;
			}
		} catch ( Exception $e ) {
			parent::rollbackTransaction();
			DelegateUtility::eccezione($e, $this, "ERROR_TYPOLOGICAL_EDIT");
			return 0;
		}
	}

	function delete($id) {
		$this->LOG_FUNCTION= "delete";
		try {
			parent::startTransaction();
			if (! empty($id)) {
				$typologicalBS= new $this->bsName();
				$typologicalBS->delete($id);
				
				parent::commitTransaction();
				$this->ok(TranslatorUtility::__translate("INFO_TYPOLOGICAL_DELETE", $this->localefile));
				return true;
			} else {
				parent::rollbackTransaction();
				DelegateUtility::idNulloDaEliminare($this, "ERROR_TYPOLOGICAL_DELETE");
				return false;
			}
		} catch ( Exception $e ) {
			parent::rollbackTransaction();
			DelegateUtility::eccezione($e, $this, "ERROR_TYPOLOGICAL_DELETE");
			return false;
		}
	}
}