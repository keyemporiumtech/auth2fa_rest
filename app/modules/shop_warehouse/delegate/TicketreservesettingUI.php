<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("TicketreservesettingBS", "modules/shop_warehouse/business");
App::uses("FileUtility", "modules/coreutils/utility");

class TicketreservesettingUI extends AppGenericUI {

	function __construct() {
		parent::__construct("TicketreservesettingUI");
		$this->localefile= "ticketreservesetting";
		$this->obj= array (
				new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
				new ObjPropertyEntity("name", null, ""),
		);
	}

	function get($id= null, $cod= null) {
		$this->LOG_FUNCTION= "get";
		try {
			if (empty($id) && empty($cod)) {
				DelegateUtility::paramsNull($this, "ERROR_TICKETRESERVESETTING_NOT_FOUND");
				return "";
			}
			$ticketreservesettingBS= new TicketreservesettingBS();
			$ticketreservesettingBS->json= $this->json;
			parent::completeByJsonFkVf($ticketreservesettingBS);
			if (! empty($cod)) {
				$ticketreservesettingBS->addCondition("cod", $cod);
			}
			$this->ok();
			return $ticketreservesettingBS->unique($id);
		} catch ( Exception $e ) {
			DelegateUtility::eccezione($e, $this, "ERROR_TICKETRESERVESETTING_NOT_FOUND");
			return "";
		}
	}

	function table($conditions= null, $orders= null, $paginate= null, $bs= null) {
		$this->LOG_FUNCTION= "table";
		try {
			$ticketreservesettingBS= ! empty($bs) ? $bs : new TicketreservesettingBS();
			$ticketreservesettingBS->json= $this->json;
			parent::completeByJsonFkVf($ticketreservesettingBS);
			parent::evalConditions($ticketreservesettingBS, $conditions);
			parent::evalOrders($ticketreservesettingBS, $orders);
			$ticketreservesettings= $ticketreservesettingBS->table($conditions, $orders, $paginate);
			parent::evalPagination($ticketreservesettingBS, $paginate);
			$this->ok();
			return parent::paginateForResponse($ticketreservesettings);
		} catch ( Exception $e ) {
			DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
			return "";
		}
	}

	function save($ticketreservesettingIn) {
		$this->LOG_FUNCTION= "save";
		try {
			parent::startTransaction();
			
			$ticketreservesetting= DelegateUtility::getEntityToSave(new TicketreservesettingBS(), $ticketreservesettingIn, $this->obj);
			
			if (! empty($ticketreservesetting)) {
				
				$ticketreservesettingBS= new TicketreservesettingBS();
				$id_ticketreservesetting= $ticketreservesettingBS->save($ticketreservesetting);
				parent::saveInGroup($ticketreservesettingBS, $id_ticketreservesetting);
				
				parent::commitTransaction();
				if (! empty($id_ticketreservesetting)) {
					$this->ok(TranslatorUtility::__translate("INFO_TICKETRESERVESETTING_SAVE", $this->localefile));
					return $id_ticketreservesetting;
				} else {
					parent::rollbackTransaction();
					DelegateUtility::nonSalvato($this, "ERROR_TICKETRESERVESETTING_SAVE");
					return 0;
				}
			} else {
				parent::rollbackTransaction();
				DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_TICKETRESERVESETTING_SAVE");
				return 0;
			}
		} catch ( Exception $e ) {
			parent::rollbackTransaction();
			DelegateUtility::eccezione($e, $this, "ERROR_TICKETRESERVESETTING_SAVE");
			return 0;
		}
	}

	function edit($id, $ticketreservesettingIn) {
		$this->LOG_FUNCTION= "edit";
		try {
			parent::startTransaction();
			
			$ticketreservesetting= DelegateUtility::getEntityToEdit(new TicketreservesettingBS(), $ticketreservesettingIn, $this->obj, $id);
			
			if (! empty($ticketreservesetting)) {
				$ticketreservesettingBS= new TicketreservesettingBS();
				$id_ticketreservesetting= $ticketreservesettingBS->save($ticketreservesetting);
				parent::saveInGroup($ticketreservesettingBS, $id_ticketreservesetting);
				parent::delInGroup($ticketreservesettingBS, $id_ticketreservesetting);
				
				parent::commitTransaction();
				if (! empty($id_ticketreservesetting)) {
					$this->ok(TranslatorUtility::__translate("INFO_TICKETRESERVESETTING_EDIT", $this->localefile));
					return $id_ticketreservesetting;
				} else {
					parent::rollbackTransaction();
					DelegateUtility::nonModificato($this, "ERROR_TICKETRESERVESETTING_EDIT");
					return 0;
				}
			} else {
				parent::rollbackTransaction();
				DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_TICKETRESERVESETTING_EDIT");
				return 0;
			}
		} catch ( Exception $e ) {
			parent::rollbackTransaction();
			DelegateUtility::eccezione($e, $this, "ERROR_TICKETRESERVESETTING_EDIT");
			return 0;
		}
	}

	function delete($id) {
		$this->LOG_FUNCTION= "delete";
		try {
			parent::startTransaction();
			if (! empty($id)) {
				$ticketreservesettingBS= new TicketreservesettingBS();
				$ticketreservesettingBS->delete($id);
				
				parent::commitTransaction();
				$this->ok(TranslatorUtility::__translate("INFO_TICKETRESERVESETTING_DELETE", $this->localefile));
				return true;
			} else {
				parent::rollbackTransaction();
				DelegateUtility::idNulloDaEliminare($this, "ERROR_TICKETRESERVESETTING_DELETE");
				return false;
			}
		} catch ( Exception $e ) {
			parent::rollbackTransaction();
			DelegateUtility::eccezione($e, $this, "ERROR_TICKETRESERVESETTING_DELETE");
			return false;
		}
	}
}
