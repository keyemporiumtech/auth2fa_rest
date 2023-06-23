<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("ConnectionManager", "Model");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("ConfirmoperationBS", "modules/authentication/business");
App::uses("SystemUtility", "modules/coreutils/utility");
App::uses("FileUtility", "modules/coreutils/utility");

class ConfirmoperationUI extends AppGenericUI {

    function __construct() {
        parent::__construct("ConfirmoperationUI");
        $this->localefile = "confirmoperation";
        $browser = SystemUtility::browser();
        $this->obj = array(
            new ObjPropertyEntity("codoperation", null, FileUtility::uuid_medium_unique()),
            new ObjPropertyEntity("description", null, ""),
            new ObjPropertyEntity("phone", null, ""),
            new ObjPropertyEntity("codsms", null, ""),
            new ObjPropertyEntity("email", null, ""),
            new ObjPropertyEntity("codemail", null, ""),
            new ObjPropertyEntity("user", null, 0),
            new ObjPropertyEntity("flgaccepted", null, 0),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_CONFIRMOPERATION_NOT_FOUND");
                return "";
            }
            $confirmoperationBS = new ConfirmoperationBS();
            $confirmoperationBS->json = $this->json;
            parent::completeByJsonFkVf($confirmoperationBS);
            if (!empty($cod)) {
                $confirmoperationBS->addCondition("codoperation", $cod);
            }
            $this->ok();
            return $confirmoperationBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_CONFIRMOPERATION_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $confirmoperationBS = !empty($bs) ? $bs : new ConfirmoperationBS();
            $confirmoperationBS->json = $this->json;
            parent::completeByJsonFkVf($confirmoperationBS);
            parent::evalConditions($confirmoperationBS, $conditions);
            parent::evalOrders($confirmoperationBS, $orders);
            $confirmoperations = $confirmoperationBS->table($conditions, $orders, $paginate);
            parent::evalPagination($confirmoperationBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($confirmoperations);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($confirmoperationIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $confirmoperation = DelegateUtility::getEntityToSave(new ConfirmoperationBS(), $confirmoperationIn, $this->obj);

            if (!empty($confirmoperation)) {

                $confirmoperationBS = new ConfirmoperationBS();
                $id_confirmoperation = $confirmoperationBS->save($confirmoperation);
                parent::saveInGroup($confirmoperationBS, $id_confirmoperation);

                parent::commitTransaction();
                if (!empty($id_confirmoperation)) {
                    $this->ok(TranslatorUtility::__translate("INFO_CONFIRMOPERATION_SAVE", $this->localefile));
                    return $id_confirmoperation;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_CONFIRMOPERATION_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_CONFIRMOPERATION_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_CONFIRMOPERATION_SAVE");
            return 0;
        }
    }

    function edit($id, $confirmoperationIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $confirmoperation = DelegateUtility::getEntityToEdit(new ConfirmoperationBS(), $confirmoperationIn, $this->obj, $id);

            if (!empty($confirmoperation)) {
                $confirmoperationBS = new ConfirmoperationBS();
                $id_confirmoperation = $confirmoperationBS->save($confirmoperation);
                parent::saveInGroup($confirmoperationBS, $id_confirmoperation);
                parent::delInGroup($confirmoperationBS, $id_confirmoperation);

                parent::commitTransaction();
                if (!empty($id_confirmoperation)) {
                    $this->ok(TranslatorUtility::__translate("INFO_CONFIRMOPERATION_EDIT", $this->localefile));
                    return $id_confirmoperation;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_CONFIRMOPERATION_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_CONFIRMOPERATION_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_CONFIRMOPERATION_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $confirmoperationBS = new ConfirmoperationBS();
                $confirmoperationBS->delete($id);
                parent::delInGroup($confirmoperationBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_CONFIRMOPERATION_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_CONFIRMOPERATION_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_CONFIRMOPERATION_DELETE");
            return false;
        }
    }

    // -------------------------- MANAGE OPERATIONS
    /**
     * Chiude tutte le operazioni con codice "cod" ancora aperte
     * @param string $cod codice operazione
     * @return boolean true se sono state chiuse tutte le operazione di tipo "cod" ancora aperte
     */
    function closeAll($cod = null) {
        $this->LOG_FUNCTION = "closeAll";
        try {
            if (empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_CONFIRMOPERATION_NOT_FOUND");
                return false;
            }

            parent::startTransaction();

            $confirmoperationBS = new ConfirmoperationBS();
            $confirmoperationBS->json = $this->json;
            $confirmoperationBS->addCondition("codoperation", $cod);
            $confirmoperationBS->addCondition("flgclosed", 0);
            $confirmoperations = $confirmoperationBS->all();

            if (!ArrayUtility::isEmpty($confirmoperations)) {
                foreach ($confirmoperations as $confirmoperation) {
                    $confirmoperationBS = new ConfirmoperationBS();
                    $confirmoperationBS->updateField($confirmoperation['Confirmoperation']['id'], "flgclosed", 1);
                }
            }
            parent::commitTransaction();

            $this->ok();
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_CONFIRMOPERATION_NOT_FOUND");
            return false;
        }
    }

    /**
     * Trova tutte le operazioni con codice "cod" e non ancora chiuse.
     * Se il pin è trovato nella lista delle operazione allora chiude automaticamente tutte le operazioni trovate.
     * @param string $cod codice operazione
     * @param string $pin pin di controllo dell'operazione
     * @return boolean true se il pin è stato trovato e le operazioni sono state chiuse
     */
    function checkAndManageOperationByCod($cod = null, $pin = null) {
        $this->LOG_FUNCTION = "checkAndManageOperationByCod";
        try {
            if (empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_CONFIRMOPERATION_NOT_FOUND");
                return false;
            }

            parent::startTransaction();

            $confirmoperationBS = new ConfirmoperationBS();
            $confirmoperationBS->json = $this->json;
            $confirmoperationBS->addCondition("codoperation", $cod);
            $confirmoperationBS->addCondition("flgclosed", 0);
            $confirmoperations = $confirmoperationBS->all();

            $founded = false;

            if (!ArrayUtility::isEmpty($confirmoperations)) {
                foreach ($confirmoperations as $confirmoperation) {
                    if ($pin == $confirmoperation['Confirmoperation']['codemail'] || $pin == $confirmoperation['Confirmoperation']['codsms']) {
                        $confirmoperation['Confirmoperation']['token'] = null;
                        $confirmoperation['Confirmoperation']['flgclosed'] = 1;
                        $confirmoperation['Confirmoperation']['flgaccepted'] = 1;
                        $confirmoperationBS = new ConfirmoperationBS();
                        $id_confirmoperation = $confirmoperationBS->save($confirmoperation);
                        $founded = true;
                    }
                }
            }
            if (!$founded) {
                DelegateUtility::errorInternal($this, "CONTROL_CODE_INVALID", "ERROR_CONFIRMOPERATION_CHECK_PIN", null, "ERROR_CONTROL_CODE_NOT_VALID", array(
                    $cod,
                    $pin,
                ));
                return false;
            }
            parent::commitTransaction();
            $this->ok();
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_CONFIRMOPERATION_NOT_FOUND");
            return false;
        }
    }

    function getOperationToken($cod = null, $flgclosed = false) {
        $this->LOG_FUNCTION = "getOperationToken";
        try {
            if (empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_CONFIRMOPERATION_NOT_FOUND");
                return "";
            }

            $confirmoperationBS = new ConfirmoperationBS();
            $confirmoperationBS->json = $this->json;
            $confirmoperationBS->addCondition("codoperation", $cod);
            $confirmoperationBS->addCondition("flgclosed", $flgclosed ? 1 : 0);
            $confirmoperations = $confirmoperationBS->all();

            if (ArrayUtility::isEmpty($confirmoperations)) {
                DelegateUtility::errorInternal($this, "EMPTY_LIST", "ERROR_CONFIRMOPERATION_NOT_FOUND", null, "ERROR_EMPTY_LIST");
                return "";
            }

            $this->ok();
            return $confirmoperations[0]['Confirmoperation']['token'];
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_CONFIRMOPERATION_NOT_FOUND");
            return "";
        }
    }
}