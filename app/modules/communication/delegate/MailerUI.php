<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("MailerBS", "modules/communication/business");
App::uses("FileUtility", "modules/coreutils/utility");

class MailerUI extends AppGenericUI {

    function __construct() {
        parent::__construct("MailerUI");
        $this->localefile = "mailer";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("name", null, ""),
            new ObjPropertyEntity("host", null, ""),
            new ObjPropertyEntity("port", null, ""),
            new ObjPropertyEntity("username", null, ""),
            new ObjPropertyEntity("password", null, ""),
            new ObjPropertyEntity("sendername", null, ""),
            new ObjPropertyEntity("senderemail", null, ""),
            new ObjPropertyEntity("crypttype", null, ""),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_MAILER_NOT_FOUND");
                return "";
            }
            $mailerBS = new MailerBS();
            $mailerBS->json = $this->json;
            parent::completeByJsonFkVf($mailerBS);
            if (!empty($cod)) {
                $mailerBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $mailerBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_MAILER_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $mailerBS = !empty($bs) ? $bs : new MailerBS();
            $mailerBS->json = $this->json;
            parent::completeByJsonFkVf($mailerBS);
            parent::evalConditions($mailerBS, $conditions);
            parent::evalOrders($mailerBS, $orders);
            $mailers = $mailerBS->table($conditions, $orders, $paginate);
            parent::evalPagination($mailerBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($mailers);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($mailerIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $mailer = DelegateUtility::getEntityToSave(new MailerBS(), $mailerIn, $this->obj);

            if (!empty($mailer)) {

                $mailerBS = new MailerBS();
                $id_mailer = $mailerBS->save($mailer);
                parent::saveInGroup($mailerBS, $id_mailer);

                parent::commitTransaction();
                if (!empty($id_mailer)) {
                    $this->ok(TranslatorUtility::__translate("INFO_MAILER_SAVE", $this->localefile));
                    return $id_mailer;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_MAILER_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_MAILER_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_MAILER_SAVE");
            return 0;
        }
    }

    function edit($id, $mailerIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $mailer = DelegateUtility::getEntityToEdit(new MailerBS(), $mailerIn, $this->obj, $id);

            if (!empty($mailer)) {
                $mailerBS = new MailerBS();
                $id_mailer = $mailerBS->save($mailer);
                parent::saveInGroup($mailerBS, $id_mailer);
                parent::delInGroup($mailerBS, $id_mailer);

                parent::commitTransaction();
                if (!empty($id_mailer)) {
                    $this->ok(TranslatorUtility::__translate("INFO_MAILER_EDIT", $this->localefile));
                    return $id_mailer;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_MAILER_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_MAILER_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_MAILER_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $mailerBS = new MailerBS();
                $mailerBS->delete($id);
                parent::delInGroup($mailerBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_MAILER_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_MAILER_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_MAILER_DELETE");
            return false;
        }
    }
}