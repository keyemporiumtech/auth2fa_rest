<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("MailcidBS", "modules/communication/business");

class MailcidUI extends AppGenericUI {

    function __construct() {
        parent::__construct("MailcidUI");
        $this->localefile = "mailcid";
        $this->obj = array(
            new ObjPropertyEntity("mail", null, 0),
            new ObjPropertyEntity("attachment", null, 0),
            new ObjPropertyEntity("cid", null, ""),
        );
    }

    function get($id = null, $cid = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cid)) {
                DelegateUtility::paramsNull($this, "ERROR_MAILCID_NOT_FOUND");
                return "";
            }
            $mailcidBS = new MailcidBS();
            $mailcidBS->json = $this->json;
            parent::completeByJsonFkVf($mailcidBS);
            if (!empty($cid)) {
                $mailcidBS->addCondition("cid", $cid);
            }
            $this->ok();
            return $mailcidBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_MAILCID_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $mailcidBS = !empty($bs) ? $bs : new MailcidBS();
            $mailcidBS->json = $this->json;
            parent::completeByJsonFkVf($mailcidBS);
            parent::evalConditions($mailcidBS, $conditions);
            parent::evalOrders($mailcidBS, $orders);
            $mailcids = $mailcidBS->table($conditions, $orders, $paginate);
            parent::evalPagination($mailcidBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($mailcids);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($mailcidIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $mailcid = DelegateUtility::getEntityToSave(new MailcidBS(), $mailcidIn, $this->obj);

            if (!empty($mailcid)) {

                $mailcidBS = new MailcidBS();
                $id_mailcid = $mailcidBS->save($mailcid);
                parent::saveInGroup($mailcidBS, $id_mailcid);

                parent::commitTransaction();
                if (!empty($id_mailcid)) {
                    $this->ok(TranslatorUtility::__translate("INFO_MAILCID_SAVE", $this->localefile));
                    return $id_mailcid;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_MAILCID_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_MAILCID_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_MAILCID_SAVE");
            return 0;
        }
    }

    function edit($id, $mailcidIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $mailcid = DelegateUtility::getEntityToEdit(new MailcidBS(), $mailcidIn, $this->obj, $id);

            if (!empty($mailcid)) {
                $mailcidBS = new MailcidBS();
                $id_mailcid = $mailcidBS->save($mailcid);
                parent::saveInGroup($mailcidBS, $id_mailcid);
                parent::delInGroup($mailcidBS, $id_mailcid);

                parent::commitTransaction();
                if (!empty($id_mailcid)) {
                    $this->ok(TranslatorUtility::__translate("INFO_MAILCID_EDIT", $this->localefile));
                    return $id_mailcid;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_MAILCID_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_MAILCID_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_MAILCID_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $mailcidBS = new MailcidBS();
                $mailcidBS->delete($id);
                parent::delInGroup($mailcidBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_MAILCID_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_MAILCID_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_MAILCID_DELETE");
            return false;
        }
    }
}
