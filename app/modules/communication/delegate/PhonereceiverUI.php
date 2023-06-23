<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("PhonereceiverBS", "modules/communication/business");
App::uses("DateUtility", "modules/coreutils/utility");

class PhonereceiverUI extends AppGenericUI {

    function __construct() {
        parent::__construct("PhonereceiverUI");
        $this->localefile = "phonereceiver";
        $this->obj = array(
            new ObjPropertyEntity("phone", null, 0),
            new ObjPropertyEntity("receivername", null, ""),
            new ObjPropertyEntity("receiverphone", null, ""),
            new ObjPropertyEntity("flgreaded", null, 0),
            new ObjPropertyEntity("dtaread", null, ""),
            new ObjPropertyEntity("dtareceive", null, DateUtility::getCurrentTime()),
        );
    }

    function get($id = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id)) {
                DelegateUtility::paramsNull($this, "ERROR_PHONERECEIVER_NOT_FOUND");
                return "";
            }
            $phonereceiverBS = new PhonereceiverBS();
            $phonereceiverBS->json = $this->json;
            parent::completeByJsonFkVf($phonereceiverBS);
            $this->ok();
            return $phonereceiverBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_PHONERECEIVER_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $phonereceiverBS = !empty($bs) ? $bs : new PhonereceiverBS();
            $phonereceiverBS->json = $this->json;
            parent::completeByJsonFkVf($phonereceiverBS);
            parent::evalConditions($phonereceiverBS, $conditions);
            parent::evalOrders($phonereceiverBS, $orders);
            $phonereceivers = $phonereceiverBS->table($conditions, $orders, $paginate);
            parent::evalPagination($phonereceiverBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($phonereceivers);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($phonereceiverIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $phonereceiver = DelegateUtility::getEntityToSave(new PhonereceiverBS(), $phonereceiverIn, $this->obj);

            if (!empty($phonereceiver)) {

                $phonereceiverBS = new PhonereceiverBS();
                $id_phonereceiver = $phonereceiverBS->save($phonereceiver);
                parent::saveInGroup($phonereceiverBS, $id_phonereceiver);

                parent::commitTransaction();
                if (!empty($id_phonereceiver)) {
                    $this->ok(TranslatorUtility::__translate("INFO_PHONERECEIVER_SAVE", $this->localefile));
                    return $id_phonereceiver;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_PHONERECEIVER_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_PHONERECEIVER_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PHONERECEIVER_SAVE");
            return 0;
        }
    }

    function edit($id, $phonereceiverIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $phonereceiver = DelegateUtility::getEntityToEdit(new PhonereceiverBS(), $phonereceiverIn, $this->obj, $id);

            if (!empty($phonereceiver)) {
                $phonereceiverBS = new PhonereceiverBS();
                $id_phonereceiver = $phonereceiverBS->save($phonereceiver);
                parent::saveInGroup($phonereceiverBS, $id_phonereceiver);
                parent::delInGroup($phonereceiverBS, $id_phonereceiver);

                parent::commitTransaction();
                if (!empty($id_phonereceiver)) {
                    $this->ok(TranslatorUtility::__translate("INFO_PHONERECEIVER_EDIT", $this->localefile));
                    return $id_phonereceiver;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_PHONERECEIVER_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_PHONERECEIVER_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PHONERECEIVER_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $phonereceiverBS = new PhonereceiverBS();
                $phonereceiverBS->delete($id);
                parent::delInGroup($phonereceiverBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_PHONERECEIVER_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_PHONERECEIVER_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PHONERECEIVER_DELETE");
            return false;
        }
    }
}
