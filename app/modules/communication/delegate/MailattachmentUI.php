<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("MailattachmentBS", "modules/communication/business");

class MailattachmentUI extends AppGenericUI {

    function __construct() {
        parent::__construct("MailattachmentUI");
        $this->localefile = "mailattachment";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, ""),
            new ObjPropertyEntity("mail", null, 0),
            new ObjPropertyEntity("attachment", null, 0),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_MAILATTACHMENT_NOT_FOUND");
                return "";
            }
            $mailattachmentBS = new MailattachmentBS();
            $mailattachmentBS->json = $this->json;
            parent::completeByJsonFkVf($mailattachmentBS);
            if (!empty($cod)) {
                $mailattachmentBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $mailattachmentBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_MAILATTACHMENT_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $mailattachmentBS = !empty($bs) ? $bs : new MailattachmentBS();
            $mailattachmentBS->json = $this->json;
            parent::completeByJsonFkVf($mailattachmentBS);
            parent::evalConditions($mailattachmentBS, $conditions);
            parent::evalOrders($mailattachmentBS, $orders);
            $mailattachments = $mailattachmentBS->table($conditions, $orders, $paginate);
            parent::evalPagination($mailattachmentBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($mailattachments);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($mailattachmentIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $mailattachment = DelegateUtility::getEntityToSave(new MailattachmentBS(), $mailattachmentIn, $this->obj);

            if (!empty($mailattachment)) {

                $mailattachmentBS = new MailattachmentBS();
                $id_mailattachment = $mailattachmentBS->save($mailattachment);
                parent::saveInGroup($mailattachmentBS, $id_mailattachment);

                parent::commitTransaction();
                if (!empty($id_mailattachment)) {
                    $this->ok(TranslatorUtility::__translate("INFO_MAILATTACHMENT_SAVE", $this->localefile));
                    return $id_mailattachment;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_MAILATTACHMENT_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_MAILATTACHMENT_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_MAILATTACHMENT_SAVE");
            return 0;
        }
    }

    function edit($id, $mailattachmentIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $mailattachment = DelegateUtility::getEntityToEdit(new MailattachmentBS(), $mailattachmentIn, $this->obj, $id);

            if (!empty($mailattachment)) {
                $mailattachmentBS = new MailattachmentBS();
                $id_mailattachment = $mailattachmentBS->save($mailattachment);
                parent::saveInGroup($mailattachmentBS, $id_mailattachment);
                parent::delInGroup($mailattachmentBS, $id_mailattachment);

                parent::commitTransaction();
                if (!empty($id_mailattachment)) {
                    $this->ok(TranslatorUtility::__translate("INFO_MAILATTACHMENT_EDIT", $this->localefile));
                    return $id_mailattachment;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_MAILATTACHMENT_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_MAILATTACHMENT_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_MAILATTACHMENT_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $mailattachmentBS = new MailattachmentBS();
                $mailattachmentBS->delete($id);
                parent::delInGroup($mailattachmentBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_MAILATTACHMENT_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_MAILATTACHMENT_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_MAILATTACHMENT_DELETE");
            return false;
        }
    }
}
