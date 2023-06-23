<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("ConnectionManager", "Model");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("ClienttokenBS", "modules/authentication/business");

class ClienttokenUI extends AppGenericUI {

    function __construct() {
        parent::__construct("ClienttokenUI");
        $this->localefile = "appclient";
        $this->obj = array(
            new ObjPropertyEntity("appname", null, ""),
            new ObjPropertyEntity("token", null, null),
        );
    }

    function get($id = null, $appname = null, $token = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($appname) && empty($token)) {
                DelegateUtility::paramsNull($this, "ERROR_CLIENTTOKEN_NOT_FOUND");
                return "";
            }
            $clienttokenBS = new ClienttokenBS();
            $clienttokenBS->json = $this->json;
            parent::completeByJsonFkVf($clienttokenBS);
            if (!empty($appname)) {
                $clienttokenBS->addCondition("appname", $appname);
            }
            if (!empty($token)) {
                $clienttokenBS->addCondition("token", $token);
            }
            $this->ok();
            return $clienttokenBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_CLIENTTOKEN_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $clienttokenBS = !empty($bs) ? $bs : new ClienttokenBS();
            $clienttokenBS->json = $this->json;
            parent::completeByJsonFkVf($clienttokenBS);
            parent::evalConditions($clienttokenBS, $conditions);
            parent::evalOrders($clienttokenBS, $orders);
            $clienttokens = $clienttokenBS->table($conditions, $orders, $paginate);
            parent::evalPagination($clienttokenBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($clienttokens);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($clienttokenIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $clienttoken = DelegateUtility::getEntityToSave(new ClienttokenBS(), $clienttokenIn, $this->obj);

            if (!empty($clienttoken)) {

                $clienttokenBS = new ClienttokenBS();
                $id_clienttoken = $clienttokenBS->save($clienttoken);
                parent::saveInGroup($clienttokenBS, $id_clienttoken);

                parent::commitTransaction();
                if (!empty($id_clienttoken)) {
                    $this->ok(TranslatorUtility::__translate("INFO_CLIENTTOKEN_SAVE", $this->localefile));
                    return $id_clienttoken;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_CLIENTTOKEN_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_CLIENTTOKEN_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_CLIENTTOKEN_SAVE");
            return 0;
        }
    }

    function edit($id, $clienttokenIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $clienttoken = DelegateUtility::getEntityToEdit(new ClienttokenBS(), $clienttokenIn, $this->obj, $id);

            if (!empty($clienttoken)) {
                $clienttokenBS = new ClienttokenBS();
                $id_clienttoken = $clienttokenBS->save($clienttoken);
                parent::saveInGroup($clienttokenBS, $id_clienttoken);
                parent::delInGroup($clienttokenBS, $id_clienttoken);

                parent::commitTransaction();
                if (!empty($id_clienttoken)) {
                    $this->ok(TranslatorUtility::__translate("INFO_CLIENTTOKEN_EDIT", $this->localefile));
                    return $id_clienttoken;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_CLIENTTOKEN_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_CLIENTTOKEN_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_CLIENTTOKEN_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $clienttokenBS = new ClienttokenBS();
                $clienttokenBS->delete($id);
                parent::delInGroup($clienttokenBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_CLIENTTOKEN_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_CLIENTTOKEN_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_CLIENTTOKEN_DELETE");
            return false;
        }
    }
}