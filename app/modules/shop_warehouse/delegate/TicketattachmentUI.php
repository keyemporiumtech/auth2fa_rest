<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("TicketattachmentBS", "modules/shop_warehouse/business");
App::uses("FileUtility", "modules/coreutils/utility");

class TicketattachmentUI extends AppGenericUI {

    function __construct() {
        parent::__construct("TicketattachmentUI");
        $this->localefile = "ticketattachment";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("ticket", null, 0),
            new ObjPropertyEntity("attachment", null, 0),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_TICKETATTACHMENT_NOT_FOUND");
                return "";
            }
            $ticketattachmentBS = new TicketattachmentBS();
            $ticketattachmentBS->json = $this->json;
            parent::completeByJsonFkVf($ticketattachmentBS);
            if (!empty($cod)) {
                $ticketattachmentBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $ticketattachmentBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_TICKETATTACHMENT_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $ticketattachmentBS = !empty($bs) ? $bs : new TicketattachmentBS();
            $ticketattachmentBS->json = $this->json;
            parent::completeByJsonFkVf($ticketattachmentBS);
            parent::evalConditions($ticketattachmentBS, $conditions);
            parent::evalOrders($ticketattachmentBS, $orders);
            $ticketattachments = $ticketattachmentBS->table($conditions, $orders, $paginate);
            parent::evalPagination($ticketattachmentBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($ticketattachments);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($ticketattachmentIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $ticketattachment = DelegateUtility::getEntityToSave(new TicketattachmentBS(), $ticketattachmentIn, $this->obj);

            if (!empty($ticketattachment)) {

                $ticketattachmentBS = new TicketattachmentBS();
                $id_ticketattachment = $ticketattachmentBS->save($ticketattachment);
                parent::saveInGroup($ticketattachmentBS, $id_ticketattachment);

                parent::commitTransaction();
                if (!empty($id_ticketattachment)) {
                    $this->ok(TranslatorUtility::__translate("INFO_TICKETATTACHMENT_SAVE", $this->localefile));
                    return $id_ticketattachment;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_TICKETATTACHMENT_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_TICKETATTACHMENT_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_TICKETATTACHMENT_SAVE");
            return 0;
        }
    }

    function edit($id, $ticketattachmentIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $ticketattachment = DelegateUtility::getEntityToEdit(new TicketattachmentBS(), $ticketattachmentIn, $this->obj, $id);

            if (!empty($ticketattachment)) {
                $ticketattachmentBS = new TicketattachmentBS();
                $id_ticketattachment = $ticketattachmentBS->save($ticketattachment);
                parent::saveInGroup($ticketattachmentBS, $id_ticketattachment);
                parent::delInGroup($ticketattachmentBS, $id_ticketattachment);

                parent::commitTransaction();
                if (!empty($id_ticketattachment)) {
                    $this->ok(TranslatorUtility::__translate("INFO_TICKETATTACHMENT_EDIT", $this->localefile));
                    return $id_ticketattachment;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_TICKETATTACHMENT_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_TICKETATTACHMENT_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_TICKETATTACHMENT_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $ticketattachmentBS = new TicketattachmentBS();
                $ticketattachmentBS->delete($id);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_TICKETATTACHMENT_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_TICKETATTACHMENT_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_TICKETATTACHMENT_DELETE");
            return false;
        }
    }
}
