<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("ConnectionManager", "Model");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("PhoneDto", "modules/communication/classes");
App::uses("PhoneBS", "modules/communication/business");
App::uses("PhonereceiverBS", "modules/communication/business");
App::uses("PhoneUser", "modules/communication/classes");

class PhoneUI extends AppGenericUI {

    function __construct() {
        parent::__construct("PhoneUI");
        $this->localefile = "phone";
        $this->obj = array(
            new ObjPropertyEntity("sendername", null, ""),
            new ObjPropertyEntity("senderphone", null, ""),
            new ObjPropertyEntity("message", null, ""),
            new ObjPropertyEntity("flgdeleted", null, ""),
            new ObjPropertyEntity("dtasend", null, date('Y-m-d H:i:s')),
        );
    }

    function get($id = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id)) {
                DelegateUtility::paramsNull($this, "ERROR_PHONE_NOT_FOUND");
                return "";
            }
            $phoneBS = new PhoneBS();
            $phoneBS->json = $this->json;
            parent::completeByJsonFkVf($phoneBS);
            $this->ok();
            return $phoneBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_PHONE_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $phoneBS = !empty($bs) ? $bs : new PhoneBS();
            $phoneBS->json = $this->json;
            parent::completeByJsonFkVf($phoneBS);
            parent::evalConditions($phoneBS, $conditions);
            parent::evalOrders($phoneBS, $orders);
            $phones = $phoneBS->table($conditions, $orders, $paginate);
            parent::evalPagination($phoneBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($phones);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($phoneIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $phone = DelegateUtility::getEntityToSave(new PhoneBS(), $phoneIn, $this->obj);

            if (!empty($phone)) {

                $phoneBS = new PhoneBS();
                $id_phone = $phoneBS->save($phone);
                parent::saveInGroup($phoneBS, $id_phone);

                parent::commitTransaction();
                if (!empty($id_phone)) {
                    $this->ok(TranslatorUtility::__translate("INFO_PHONE_SAVE", $this->localefile));
                    return $id_phone;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_PHONE_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_PHONE_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PHONE_SAVE");
            return 0;
        }
    }

    function edit($id, $phoneIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $phone = DelegateUtility::getEntityToEdit(new PhoneBS(), $phoneIn, $this->obj, $id);

            if (!empty($phone)) {
                $phoneBS = new PhoneBS();
                $id_phone = $phoneBS->save($phone);
                parent::saveInGroup($phoneBS, $id_phone);
                parent::delInGroup($phoneBS, $id_phone);

                parent::commitTransaction();
                if (!empty($id_phone)) {
                    $this->ok(TranslatorUtility::__translate("INFO_PHONE_EDIT", $this->localefile));
                    return $id_phone;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_PHONE_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_PHONE_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PHONE_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $phoneBS = new PhoneBS();
                $phoneBS->delete($id);
                parent::delInGroup($phoneBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_PHONE_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_PHONE_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PHONE_DELETE");
            return false;
        }
    }

    // ------------------------ PLUGIN INTERACTION
    function send($senderIn, $destinatorsIn, $body, $phoneConfig = null, $flgSend = true) {
        $this->LOG_FUNCTION = "send";
        $savedFiles = array();
        try {
            parent::startTransaction();

            $objSender = DelegateUtility::getObj($this->json, $senderIn); // PhoneUser
            $listDestinators = DelegateUtility::getObjList($this->json, $destinatorsIn); // PhoneUser

            // manage required parameters
            if (empty($objSender)) {
                DelegateUtility::errorInternal($this, "PARAM_NULL", "ERROR_PHONE_SEND", null, "ERROR_PHONE_SEND_NOT_SENDER");
                return false;
            }

            if (ArrayUtility::isEmpty($listDestinators)) {
                DelegateUtility::errorInternal($this, "PARAM_NULL", "ERROR_PHONE_SEND", null, "ERROR_PHONE_SEND_NOT_DESTINATORS");
                return false;
            }

            if (empty($body)) {
                DelegateUtility::errorInternal($this, "PARAM_NULL", "ERROR_PHONE_SEND", null, "ERROR_PHONE_SEND_NOT_BODY");
                return false;
            }

            // sender
            $sender = new PhoneUser();
            $sender->phone = $objSender->phone;
            $sender->name = $objSender->name;

            // destinators
            $destinators = array();
            $destinator = null;
            foreach ($listDestinators as $objDestinator) {
                $destinator = new PhoneUser();
                $destinator->phone = $objDestinator->phone;
                $destinator->name = $objDestinator->name;
                array_push($destinators, $destinator);
            }

            // SEND EPHONE
            $flg_send = true;
            /*
             * if (Enables::get("phpphoneer")) {
             * $flg_send= PhoneUtility::sendPHPPhoneer($sender, $subject, $destinators, $cc, $ccn, $attachments, $cids, $html, $phoneConfig);
             * if (! $flg_send) {
             * DelegateUtility::errorInternal($this, "PLUGIN_ERROR", "ERROR_PHONE_SEND", null , "ERROR_PHPPHONE_NOT_SENDED");
             * return false;
             * }
             * }
             */

            // SAVE PHONE
            $phoneBS = new PhoneBS();
            $phone = $phoneBS->instance();
            $phone['Phone']['sendername'] = $sender->name;
            $phone['Phone']['senderphone'] = $sender->phone;
            $phone['Phone']['message'] = $body;
            $phone['Phone']['dtasend'] = date('Y-m-d H:i:s');
            $id_phone = $phoneBS->save($phone);

            foreach ($destinators as $destinator) {
                $receiverBS = new PhonereceiverBS();
                $receiver = $receiverBS->instance();
                $receiver['Phonereceive']['phone'] = $id_phone;
                $receiver['Phonereceive']['receivername'] = $destinator->name;
                $receiver['Phonereceive']['receiverphone'] = $destinator->phone;
                $receiver['Phonereceive']['dtareceive'] = date('Y-m-d H:i:s');
                $id_receive = $receiverBS->save($receiver);
            }

            parent::commitTransaction();

            $this->ok(TranslatorUtility::__translate("INFO_PHONE_SEND", $this->localefile));
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_PHONE_SEND");
            return false;
        }
    }

    function getRead($id = null) {
        $this->LOG_FUNCTION = "getRead";
        try {
            if (empty($id)) {
                DelegateUtility::paramsNull($this, "ERROR_PHONE_NOT_FOUND");
                return "";
            }
            $dto = $this->getPhoneDto($id);

            $this->ok();
            return $this->json ? json_encode($dto) : $dto;
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_PHONE_NOT_FOUND");
            return "";
        }
    }

    function tableRead($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "tableRead";
        try {
            $phoneBS = !empty($bs) ? $bs : new PhoneBS();
            parent::completeByJsonFkVf($phoneBS);
            parent::evalConditions($phoneBS, $conditions);
            parent::evalOrders($phoneBS, $orders);
            $phones = $phoneBS->table($conditions, $orders, $paginate);
            parent::evalPagination($phoneBS, $paginate);

            $dtos = array();
            foreach ($phones as $phone) {
                array_push($dtos, $this->getPhoneDto($phone['Phone']['id']));
            }
            $this->ok();
            return parent::paginateForResponse(json_encode($dtos));
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    // -------------------------- INNER UTILITY
    private function getPhoneDto($id) {
        try {

            $dto = new PhoneDto();

            $phoneBS = new PhoneBS();
            $phone = $phoneBS->unique($id);
            $dto->phone = $phone;
            $dto->body = $phone['Phone']['message'];

            $receiverBS = new PhonereceiverBS();
            $receiverBS->addCondition("phone", $phone['Phone']['id']);
            $dto->destinators = $receiverBS->all();

            return $dto;
        } catch (Exception $e) {
            throw $e;
        }
    }
}