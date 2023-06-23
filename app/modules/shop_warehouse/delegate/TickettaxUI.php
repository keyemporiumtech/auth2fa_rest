<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("TickettaxBS", "modules/shop_warehouse/business");
App::uses("FileUtility", "modules/coreutils/utility");

class TickettaxUI extends AppGenericUI {

	function __construct() {
		parent::__construct("TickettaxUI");
		$this->localefile= "tickettax";
		$this->obj= array (
				new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
				new ObjPropertyEntity("name", null, ""),
		);
	}

	function get($id= null, $cod= null) {
		$this->LOG_FUNCTION= "get";
		try {
			if (empty($id) && empty($cod)) {
				DelegateUtility::paramsNull($this, "ERROR_TICKETTAX_NOT_FOUND");
				return "";
			}
			$tickettaxBS= new TickettaxBS();
			$tickettaxBS->json= $this->json;
			parent::completeByJsonFkVf($tickettaxBS);
			if (! empty($cod)) {
				$tickettaxBS->addCondition("cod", $cod);
			}
			$this->ok();
			return $tickettaxBS->unique($id);
		} catch ( Exception $e ) {
			DelegateUtility::eccezione($e, $this, "ERROR_TICKETTAX_NOT_FOUND");
			return "";
		}
	}

	function table($conditions= null, $orders= null, $paginate= null, $bs= null) {
		$this->LOG_FUNCTION= "table";
		try {
			$tickettaxBS= ! empty($bs) ? $bs : new TickettaxBS();
			$tickettaxBS->json= $this->json;
			parent::completeByJsonFkVf($tickettaxBS);
			parent::evalConditions($tickettaxBS, $conditions);
			parent::evalOrders($tickettaxBS, $orders);
			$tickettaxs= $tickettaxBS->table($conditions, $orders, $paginate);
			parent::evalPagination($tickettaxBS, $paginate);
			$this->ok();
			return parent::paginateForResponse($tickettaxs);
		} catch ( Exception $e ) {
			DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
			return "";
		}
	}

	function save($tickettaxIn) {
		$this->LOG_FUNCTION= "save";
		try {
			parent::startTransaction();
			
			$tickettax= DelegateUtility::getEntityToSave(new TickettaxBS(), $tickettaxIn, $this->obj);
			
			if (! empty($tickettax)) {
				
				$tickettaxBS= new TickettaxBS();
				$id_tickettax= $tickettaxBS->save($tickettax);
				parent::saveInGroup($tickettaxBS, $id_tickettax);
				
				parent::commitTransaction();
				if (! empty($id_tickettax)) {
					$this->ok(TranslatorUtility::__translate("INFO_TICKETTAX_SAVE", $this->localefile));
					return $id_tickettax;
				} else {
					parent::rollbackTransaction();
					DelegateUtility::nonSalvato($this, "ERROR_TICKETTAX_SAVE");
					return 0;
				}
			} else {
				parent::rollbackTransaction();
				DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_TICKETTAX_SAVE");
				return 0;
			}
		} catch ( Exception $e ) {
			parent::rollbackTransaction();
			DelegateUtility::eccezione($e, $this, "ERROR_TICKETTAX_SAVE");
			return 0;
		}
	}

	function edit($id, $tickettaxIn) {
		$this->LOG_FUNCTION= "edit";
		try {
			parent::startTransaction();
			
			$tickettax= DelegateUtility::getEntityToEdit(new TickettaxBS(), $tickettaxIn, $this->obj, $id);
			
			if (! empty($tickettax)) {
				$tickettaxBS= new TickettaxBS();
				$id_tickettax= $tickettaxBS->save($tickettax);
				parent::saveInGroup($tickettaxBS, $id_tickettax);
				parent::delInGroup($tickettaxBS, $id_tickettax);
				
				parent::commitTransaction();
				if (! empty($id_tickettax)) {
					$this->ok(TranslatorUtility::__translate("INFO_TICKETTAX_EDIT", $this->localefile));
					return $id_tickettax;
				} else {
					parent::rollbackTransaction();
					DelegateUtility::nonModificato($this, "ERROR_TICKETTAX_EDIT");
					return 0;
				}
			} else {
				parent::rollbackTransaction();
				DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_TICKETTAX_EDIT");
				return 0;
			}
		} catch ( Exception $e ) {
			parent::rollbackTransaction();
			DelegateUtility::eccezione($e, $this, "ERROR_TICKETTAX_EDIT");
			return 0;
		}
	}

	function delete($id) {
		$this->LOG_FUNCTION= "delete";
		try {
			parent::startTransaction();
			if (! empty($id)) {
				$tickettaxBS= new TickettaxBS();
				$tickettaxBS->delete($id);
				
				parent::commitTransaction();
				$this->ok(TranslatorUtility::__translate("INFO_TICKETTAX_DELETE", $this->localefile));
				return true;
			} else {
				parent::rollbackTransaction();
				DelegateUtility::idNulloDaEliminare($this, "ERROR_TICKETTAX_DELETE");
				return false;
			}
		} catch ( Exception $e ) {
			parent::rollbackTransaction();
			DelegateUtility::eccezione($e, $this, "ERROR_TICKETTAX_DELETE");
			return false;
		}
	}
}
