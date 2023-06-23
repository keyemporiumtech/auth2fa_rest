<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("MailreceiverBS", "modules/communication/business");
App::uses("DateUtility", "modules/coreutils/utility");

class MailreceiverUI extends AppGenericUI {

    function __construct() {
        parent::__construct("MailreceiverUI");
        $this->localefile = "mailreceiver";
        $this->obj = array(
            new ObjPropertyEntity("mail", null, 0),
            new ObjPropertyEntity("receivername", null, ""),
            new ObjPropertyEntity("receiveremail", null, ""),
            new ObjPropertyEntity("flgcc", null, 0),
            new ObjPropertyEntity("flgccn", null, 0),
            new ObjPropertyEntity("flgreaded", null, 0),
            new ObjPropertyEntity("dtaread", null, ""),
            new ObjPropertyEntity("dtareceive", null, DateUtility::getCurrentTime()),
        );
    }

    function get($id = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id)) {
                DelegateUtility::paramsNull($this, "ERROR_MAILRECEIVER_NOT_FOUND");
                return "";
            }
            $mailreceiverBS = new MailreceiverBS();
            $mailreceiverBS->json = $this->json;
            parent::completeByJsonFkVf($mailreceiverBS);
            $this->ok();
            return $mailreceiverBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_MAILRECEIVER_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $mailreceiverBS = !empty($bs) ? $bs : new MailreceiverBS();
            $mailreceiverBS->json = $this->json;
            parent::completeByJsonFkVf($mailreceiverBS);
            parent::evalConditions($mailreceiverBS, $conditions);
            parent::evalOrders($mailreceiverBS, $orders);
            $mailreceivers = $mailreceiverBS->table($conditions, $orders, $paginate);
            parent::evalPagination($mailreceiverBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($mailreceivers);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($mailreceiverIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $mailreceiver = DelegateUtility::getEntityToSave(new MailreceiverBS(), $mailreceiverIn, $this->obj);

            if (!empty($mailreceiver)) {

                $mailreceiverBS = new MailreceiverBS();
                $id_mailreceiver = $mailreceiverBS->save($mailreceiver);
                parent::saveInGroup($mailreceiverBS, $id_mailreceiver);

                parent::commitTransaction();
                if (!empty($id_mailreceiver)) {
                    $this->ok(TranslatorUtility::__translate("INFO_MAILRECEIVER_SAVE", $this->localefile));
                    return $id_mailreceiver;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_MAILRECEIVER_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_MAILRECEIVER_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_MAILRECEIVER_SAVE");
            return 0;
        }
    }

    function edit($id, $mailreceiverIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $mailreceiver = DelegateUtility::getEntityToEdit(new MailreceiverBS(), $mailreceiverIn, $this->obj, $id);

            if (!empty($mailreceiver)) {
                $mailreceiverBS = new MailreceiverBS();
                $id_mailreceiver = $mailreceiverBS->save($mailreceiver);
                parent::saveInGroup($mailreceiverBS, $id_mailreceiver);
                parent::delInGroup($mailreceiverBS, $id_mailreceiver);

                parent::commitTransaction();
                if (!empty($id_mailreceiver)) {
                    $this->ok(TranslatorUtility::__translate("INFO_MAILRECEIVER_EDIT", $this->localefile));
                    return $id_mailreceiver;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_MAILRECEIVER_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_MAILRECEIVER_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_MAILRECEIVER_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $mailreceiverBS = new MailreceiverBS();
                $mailreceiverBS->delete($id);
                parent::delInGroup($mailreceiverBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_MAILRECEIVER_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_MAILRECEIVER_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_MAILRECEIVER_DELETE");
            return false;
        }
    }
}
