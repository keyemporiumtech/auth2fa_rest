<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("ActivityreferenceBS", "modules/authentication/business");
App::uses("FileUtility", "modules/coreutils/utility");
App::uses("ActivityBS", "modules/authentication/business");
App::uses("ContactreferenceUI", "modules/authentication/delegate");

class ActivityreferenceUI extends AppGenericUI {

    function __construct() {
        parent::__construct("ActivityreferenceUI");
        $this->localefile = "activityreference";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("flgprincipal", null, 0),
            new ObjPropertyEntity("contactreference", null, 0),
            new ObjPropertyEntity("activity", null, 0),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_ACTIVITYREFERENCE_NOT_FOUND");
                return "";
            }
            $activityreferenceBS = new ActivityreferenceBS();
            $activityreferenceBS->json = $this->json;
            parent::completeByJsonFkVf($activityreferenceBS);
            if (!empty($cod)) {
                $activityreferenceBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $activityreferenceBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_ACTIVITYREFERENCE_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $activityreferenceBS = !empty($bs) ? $bs : new ActivityreferenceBS();
            $activityreferenceBS->json = $this->json;
            parent::completeByJsonFkVf($activityreferenceBS);
            parent::evalConditions($activityreferenceBS, $conditions);
            parent::evalOrders($activityreferenceBS, $orders);
            $activityreferences = $activityreferenceBS->table($conditions, $orders, $paginate);
            parent::evalPagination($activityreferenceBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($activityreferences);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($activityreferenceIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $activityreference = DelegateUtility::getEntityToSave(new ActivityreferenceBS(), $activityreferenceIn, $this->obj);

            if (!empty($activityreference)) {

                $activityreferenceBS = new ActivityreferenceBS();
                $id_activityreference = $activityreferenceBS->save($activityreference);
                parent::saveInGroup($activityreferenceBS, $id_activityreference);

                parent::commitTransaction();
                if (!empty($id_activityreference)) {
                    $this->ok(TranslatorUtility::__translate("INFO_ACTIVITYREFERENCE_SAVE", $this->localefile));
                    return $id_activityreference;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_ACTIVITYREFERENCE_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_ACTIVITYREFERENCE_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_ACTIVITYREFERENCE_SAVE");
            return 0;
        }
    }

    function edit($id, $activityreferenceIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $activityreference = DelegateUtility::getEntityToEdit(new ActivityreferenceBS(), $activityreferenceIn, $this->obj, $id);

            if (!empty($activityreference)) {
                $activityreferenceBS = new ActivityreferenceBS();
                $id_activityreference = $activityreferenceBS->save($activityreference);
                parent::saveInGroup($activityreferenceBS, $id_activityreference);
                parent::delInGroup($activityreferenceBS, $id_activityreference);

                parent::commitTransaction();
                if (!empty($id_activityreference)) {
                    $this->ok(TranslatorUtility::__translate("INFO_ACTIVITYREFERENCE_EDIT", $this->localefile));
                    return $id_activityreference;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_ACTIVITYREFERENCE_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_ACTIVITYREFERENCE_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_ACTIVITYREFERENCE_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $activityreferenceBS = new ActivityreferenceBS();
                $activityreferenceBS->delete($id);
                parent::delInGroup($activityreferenceBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_ACTIVITYREFERENCE_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_ACTIVITYREFERENCE_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_ACTIVITYREFERENCE_DELETE");
            return false;
        }
    }

    // ------------- PRINCIPAL
    function getPrincipal($id_activity = null, $piva = null, $type = null) {
        $this->LOG_FUNCTION = "getPrincipal";
        try {
            if (empty($id_activity) && empty($piva)) {
                DelegateUtility::paramsNull($this, "ERROR_ACTIVITYREFERENCE_NOT_FOUND");
                return "";
            }
            if (empty($type)) {
                DelegateUtility::paramsNull($this, "ERROR_ACTIVITYREFERENCE_NOT_FOUND");
                return "";
            }
            $id_activity = DelegateUtility::getEntityIdByFields(new ActivityBS(), array(
                "piva" => $piva,
            ), $id_activity);
            $activityreferenceBS = new ActivityreferenceBS();
            $activityreferenceBS->json = $this->json;
            parent::completeByJsonFkVf($activityreferenceBS);
            $activityreferenceBS->addCondition("activity", $id_activity);
            $activityreferenceBS->addCondition("tpcontactreference", $type);
            $activityreferenceBS->addCondition("flgprincipal", 1);

            $this->ok();
            return $activityreferenceBS->unique();
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_ACTIVITYREFERENCE_NOT_FOUND");
            return "";
        }
    }

    function setPrincipal($id_activity = null, $piva = null, $id_activityreference = null, $cod_activityreference = null, $type = null) {
        $this->LOG_FUNCTION = "setPrincipal";
        try {
            if ((empty($id_activity) && empty($piva)) || (empty($id_activityreference) && empty($cod_activityreference))) {
                DelegateUtility::paramsNull($this, "ERROR_ACTIVITYREFERENCE_SET_PRINCIPAL");
                return false;
            }
            if (empty($type)) {
                DelegateUtility::paramsNull($this, "ERROR_ACTIVITYREFERENCE_NOT_FOUND");
                return "";
            }
            $id_activity = DelegateUtility::getEntityIdByFields(new ActivityBS(), array(
                "piva" => $piva,
            ), $id_activity);

            $id_activityreference = DelegateUtility::getEntityIdByFields(new ActivityreferenceBS(), array(
                "cod" => $cod_activityreference,
                "tpcontactreference" => $type,
            ), $id_activityreference);

            parent::startTransaction();

            $activityreferenceBS = new ActivityreferenceBS();
            $activityreferenceBS->resetPrincipal($id_activity, $type, $this->groups, $this->likegroups, $this->json);

            $activityreferenceBS = new ActivityreferenceBS();
            $activityreferenceBS->updateField($id_activityreference, "flgprincipal", 1);

            parent::commitTransaction();

            $this->ok(TranslatorUtility::__translate("INFO_ACTIVITYREFERENCE_SET_PRINCIPAL", $this->localefile));
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_ACTIVITYREFERENCE_SET_PRINCIPAL");
            return false;
        }
    }

    // ------------- RELATION
    function saveRelation($id_activity, $contactreferenceIn, $tpcontactreference, $flgprincipal = false) {
        $this->LOG_FUNCTION = "saveRelation";
        try {
            parent::startTransaction();

            $contactreferenceUI = new ContactreferenceUI();
            $contactreferenceUI->json = $this->json;
            $contactreferenceUI->transactional = true;
            $id_contactreference = $contactreferenceUI->save($contactreferenceIn);
            if (empty($id_contactreference)) {
                parent::rollbackTransaction();
                parent::mappingDelegate($contactreferenceUI);
                return 0;
            }

            $activitycontactreferenceBS = new ActivityreferenceBS();
            $activitycontactreference = $activitycontactreferenceBS->instance();
            $activitycontactreference['Activityreference']['cod'] = FileUtility::uuid_medium_unique();
            $activitycontactreference['Activityreference']['activity'] = $id_activity;
            $activitycontactreference['Activityreference']['contactreference'] = $id_contactreference;
            $activitycontactreference['Activityreference']['tpcontactreference'] = $tpcontactreference;
            $id_activitycontactreference = $activitycontactreferenceBS->save($activitycontactreference);
            parent::saveInGroup($activitycontactreferenceBS, $id_activitycontactreference);

            if (!empty($id_activitycontactreference)) {
                if ($flgprincipal) {
                    $activityreferenceBS = new ActivityreferenceBS();
                    $activityreferenceBS->resetPrincipal($id_activity, $tpcontactreference);

                    $activityreferenceBS = new ActivityreferenceBS();
                    $activityreferenceBS->updateField($id_activitycontactreference, "flgprincipal", 1);
                }
                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_ACTIVITYREFERENCE_SAVE", $this->localefile));
                return $id_activitycontactreference;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::nonSalvato($this, "ERROR_ACTIVITYREFERENCE_SAVE");
                return 0;
            }

        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_ACTIVITYREFERENCE_SAVE");
            return 0;
        }
    }
}
