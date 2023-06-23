<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("UserreportBS", "modules/authentication/business");
App::uses("FileUtility", "modules/coreutils/utility");
App::uses("SystemUtility", "modules/coreutils/utility");

class UserreportUI extends AppGenericUI {

    function __construct() {
        parent::__construct("UserreportUI");
        $this->localefile = "userreport";
        $this->obj = array(
            new ObjPropertyEntity("codoperation", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("description", null, ""),
            new ObjPropertyEntity("sessionid", null, CakeSession::id()),
            new ObjPropertyEntity("ip", null, SystemUtility::getIPClient()),
            new ObjPropertyEntity("os", null, SystemUtility::getPlatormInfo()),
            new ObjPropertyEntity("browser", null, SystemUtility::browser()['name']),
            new ObjPropertyEntity("browser_version", null, SystemUtility::browser()['version']),
            new ObjPropertyEntity("user", null, 0),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_USERREPORT_NOT_FOUND");
                return "";
            }
            $userreportBS = new UserreportBS();
            $userreportBS->json = $this->json;
            parent::completeByJsonFkVf($userreportBS);
            if (!empty($cod)) {
                $userreportBS->addCondition("codoperation", $cod);
            }
            $this->ok();
            return $userreportBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_USERREPORT_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $userreportBS = !empty($bs) ? $bs : new UserreportBS();
            $userreportBS->json = $this->json;
            parent::completeByJsonFkVf($userreportBS);
            parent::evalConditions($userreportBS, $conditions);
            parent::evalOrders($userreportBS, $orders);
            $userreports = $userreportBS->table($conditions, $orders, $paginate);
            parent::evalPagination($userreportBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($userreports);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($userreportIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $userreport = DelegateUtility::getEntityToSave(new UserreportBS(), $userreportIn, $this->obj);

            if (!empty($userreport)) {

                $userreportBS = new UserreportBS();
                $id_userreport = $userreportBS->save($userreport);
                parent::saveInGroup($userreportBS, $id_userreport);

                parent::commitTransaction();
                if (!empty($id_userreport)) {
                    $this->ok(TranslatorUtility::__translate("INFO_USERREPORT_SAVE", $this->localefile));
                    return $id_userreport;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_USERREPORT_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_USERREPORT_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_USERREPORT_SAVE");
            return 0;
        }
    }

    function edit($id, $userreportIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $userreport = DelegateUtility::getEntityToEdit(new UserreportBS(), $userreportIn, $this->obj, $id);

            if (!empty($userreport)) {
                $userreportBS = new UserreportBS();
                $id_userreport = $userreportBS->save($userreport);
                parent::saveInGroup($userreportBS, $id_userreport);
                parent::delInGroup($userreportBS, $id_userreport);

                parent::commitTransaction();
                if (!empty($id_userreport)) {
                    $this->ok(TranslatorUtility::__translate("INFO_USERREPORT_EDIT", $this->localefile));
                    return $id_userreport;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_USERREPORT_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_USERREPORT_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_USERREPORT_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $userreportBS = new UserreportBS();
                $userreportBS->delete($id);
                parent::delInGroup($userreportBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_USERREPORT_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_USERREPORT_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_USERREPORT_DELETE");
            return false;
        }
    }
}
