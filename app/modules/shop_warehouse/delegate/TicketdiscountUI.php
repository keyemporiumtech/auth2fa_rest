<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("TicketdiscountBS", "modules/shop_warehouse/business");
App::uses("FileUtility", "modules/coreutils/utility");

class TicketdiscountUI extends AppGenericUI {

	function __construct() {
		parent::__construct("TicketdiscountUI");
		$this->localefile= "ticketdiscount";
		$this->obj= array (
				new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
				new ObjPropertyEntity("name", null, ""),
		);
	}

	function get($id= null, $cod= null) {
		$this->LOG_FUNCTION= "get";
		try {
			if (empty($id) && empty($cod)) {
				DelegateUtility::paramsNull($this, "ERROR_TICKETDISCOUNT_NOT_FOUND");
				return "";
			}
			$ticketdiscountBS= new TicketdiscountBS();
			$ticketdiscountBS->json= $this->json;
			parent::completeByJsonFkVf($ticketdiscountBS);
			if (! empty($cod)) {
				$ticketdiscountBS->addCondition("cod", $cod);
			}
			$this->ok();
			return $ticketdiscountBS->unique($id);
		} catch ( Exception $e ) {
			DelegateUtility::eccezione($e, $this, "ERROR_TICKETDISCOUNT_NOT_FOUND");
			return "";
		}
	}

	function table($conditions= null, $orders= null, $paginate= null, $bs= null) {
		$this->LOG_FUNCTION= "table";
		try {
			$ticketdiscountBS= ! empty($bs) ? $bs : new TicketdiscountBS();
			$ticketdiscountBS->json= $this->json;
			parent::completeByJsonFkVf($ticketdiscountBS);
			parent::evalConditions($ticketdiscountBS, $conditions);
			parent::evalOrders($ticketdiscountBS, $orders);
			$ticketdiscounts= $ticketdiscountBS->table($conditions, $orders, $paginate);
			parent::evalPagination($ticketdiscountBS, $paginate);
			$this->ok();
			return parent::paginateForResponse($ticketdiscounts);
		} catch ( Exception $e ) {
			DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
			return "";
		}
	}

	function save($ticketdiscountIn) {
		$this->LOG_FUNCTION= "save";
		try {
			parent::startTransaction();
			
			$ticketdiscount= DelegateUtility::getEntityToSave(new TicketdiscountBS(), $ticketdiscountIn, $this->obj);
			
			if (! empty($ticketdiscount)) {
				
				$ticketdiscountBS= new TicketdiscountBS();
				$id_ticketdiscount= $ticketdiscountBS->save($ticketdiscount);
				parent::saveInGroup($ticketdiscountBS, $id_ticketdiscount);
				
				parent::commitTransaction();
				if (! empty($id_ticketdiscount)) {
					$this->ok(TranslatorUtility::__translate("INFO_TICKETDISCOUNT_SAVE", $this->localefile));
					return $id_ticketdiscount;
				} else {
					parent::rollbackTransaction();
					DelegateUtility::nonSalvato($this, "ERROR_TICKETDISCOUNT_SAVE");
					return 0;
				}
			} else {
				parent::rollbackTransaction();
				DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_TICKETDISCOUNT_SAVE");
				return 0;
			}
		} catch ( Exception $e ) {
			parent::rollbackTransaction();
			DelegateUtility::eccezione($e, $this, "ERROR_TICKETDISCOUNT_SAVE");
			return 0;
		}
	}

	function edit($id, $ticketdiscountIn) {
		$this->LOG_FUNCTION= "edit";
		try {
			parent::startTransaction();
			
			$ticketdiscount= DelegateUtility::getEntityToEdit(new TicketdiscountBS(), $ticketdiscountIn, $this->obj, $id);
			
			if (! empty($ticketdiscount)) {
				$ticketdiscountBS= new TicketdiscountBS();
				$id_ticketdiscount= $ticketdiscountBS->save($ticketdiscount);
				parent::saveInGroup($ticketdiscountBS, $id_ticketdiscount);
				parent::delInGroup($ticketdiscountBS, $id_ticketdiscount);
				
				parent::commitTransaction();
				if (! empty($id_ticketdiscount)) {
					$this->ok(TranslatorUtility::__translate("INFO_TICKETDISCOUNT_EDIT", $this->localefile));
					return $id_ticketdiscount;
				} else {
					parent::rollbackTransaction();
					DelegateUtility::nonModificato($this, "ERROR_TICKETDISCOUNT_EDIT");
					return 0;
				}
			} else {
				parent::rollbackTransaction();
				DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_TICKETDISCOUNT_EDIT");
				return 0;
			}
		} catch ( Exception $e ) {
			parent::rollbackTransaction();
			DelegateUtility::eccezione($e, $this, "ERROR_TICKETDISCOUNT_EDIT");
			return 0;
		}
	}

	function delete($id) {
		$this->LOG_FUNCTION= "delete";
		try {
			parent::startTransaction();
			if (! empty($id)) {
				$ticketdiscountBS= new TicketdiscountBS();
				$ticketdiscountBS->delete($id);
				
				parent::commitTransaction();
				$this->ok(TranslatorUtility::__translate("INFO_TICKETDISCOUNT_DELETE", $this->localefile));
				return true;
			} else {
				parent::rollbackTransaction();
				DelegateUtility::idNulloDaEliminare($this, "ERROR_TICKETDISCOUNT_DELETE");
				return false;
			}
		} catch ( Exception $e ) {
			parent::rollbackTransaction();
			DelegateUtility::eccezione($e, $this, "ERROR_TICKETDISCOUNT_DELETE");
			return false;
		}
	}
}
