<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("ActivityaddressBS", "modules/authentication/business");
App::uses("FileUtility", "modules/coreutils/utility");
App::uses("ActivityBS", "modules/authentication/business");
App::uses("AddressUI", "modules/localesystem/delegate");

class ActivityaddressUI extends AppGenericUI {

    function __construct() {
        parent::__construct("ActivityaddressUI");
        $this->localefile = "activityaddress";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("flgprincipal", null, 0),
            new ObjPropertyEntity("address", null, 0),
            new ObjPropertyEntity("activity", null, 0),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_ACTIVITYADDRESS_NOT_FOUND");
                return "";
            }
            $activityaddressBS = new ActivityaddressBS();
            $activityaddressBS->json = $this->json;
            parent::completeByJsonFkVf($activityaddressBS);
            if (!empty($cod)) {
                $activityaddressBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $activityaddressBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_ACTIVITYADDRESS_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $activityaddressBS = !empty($bs) ? $bs : new ActivityaddressBS();
            $activityaddressBS->json = $this->json;
            parent::completeByJsonFkVf($activityaddressBS);
            parent::evalConditions($activityaddressBS, $conditions);
            parent::evalOrders($activityaddressBS, $orders);
            $activityaddress = $activityaddressBS->table($conditions, $orders, $paginate);
            parent::evalPagination($activityaddressBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($activityaddress);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($activityaddressIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $activityaddress = DelegateUtility::getEntityToSave(new ActivityaddressBS(), $activityaddressIn, $this->obj);

            if (!empty($activityaddress)) {

                $activityaddressBS = new ActivityaddressBS();
                $id_activityaddress = $activityaddressBS->save($activityaddress);
                parent::saveInGroup($activityaddressBS, $id_activityaddress);

                parent::commitTransaction();
                if (!empty($id_activityaddress)) {
                    $this->ok(TranslatorUtility::__translate("INFO_ACTIVITYADDRESS_SAVE", $this->localefile));
                    return $id_activityaddress;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_ACTIVITYADDRESS_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_ACTIVITYADDRESS_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_ACTIVITYADDRESS_SAVE");
            return 0;
        }
    }

    function edit($id, $activityaddressIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $activityaddress = DelegateUtility::getEntityToEdit(new ActivityaddressBS(), $activityaddressIn, $this->obj, $id);

            if (!empty($activityaddress)) {
                $activityaddressBS = new ActivityaddressBS();
                $id_activityaddress = $activityaddressBS->save($activityaddress);
                parent::saveInGroup($activityaddressBS, $id_activityaddress);
                parent::delInGroup($activityaddressBS, $id_activityaddress);

                parent::commitTransaction();
                if (!empty($id_activityaddress)) {
                    $this->ok(TranslatorUtility::__translate("INFO_ACTIVITYADDRESS_EDIT", $this->localefile));
                    return $id_activityaddress;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_ACTIVITYADDRESS_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_ACTIVITYADDRESS_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_ACTIVITYADDRESS_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $activityaddressBS = new ActivityaddressBS();
                $activityaddressBS->delete($id);
                parent::delInGroup($activityaddressBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_ACTIVITYADDRESS_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_ACTIVITYADDRESS_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_ACTIVITYADDRESS_DELETE");
            return false;
        }
    }

    // ------------- PRINCIPAL
    function getPrincipal($id_activity = null, $piva = null) {
        $this->LOG_FUNCTION = "getPrincipal";
        try {
            if (empty($id_activity) && empty($piva)) {
                DelegateUtility::paramsNull($this, "ERROR_ACTIVITYADDRESS_NOT_FOUND");
                return "";
            }
            $id_activity = DelegateUtility::getEntityIdByFields(new ActivityBS(), array(
                "piva" => $piva,
            ), $id_activity);
            $activityaddressBS = new ActivityaddressBS();
            $activityaddressBS->json = $this->json;
            parent::completeByJsonFkVf($activityaddressBS);
            $activityaddressBS->addCondition("activity", $id_activity);
            $activityaddressBS->addCondition("flgprincipal", 1);

            $this->ok();
            return $activityaddressBS->unique();
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_ACTIVITYADDRESS_NOT_FOUND");
            return "";
        }
    }

    function setPrincipal($id_activity = null, $piva = null, $id_activityaddress = null, $cod_activityaddress = null) {
        $this->LOG_FUNCTION = "setPrincipal";
        try {
            if ((empty($id_activity) && empty($piva)) || (empty($id_activityaddress) && empty($cod_activityaddress))) {
                DelegateUtility::paramsNull($this, "ERROR_ACTIVITYADDRESS_SET_PRINCIPAL");
                return false;
            }
            $id_activity = DelegateUtility::getEntityIdByFields(new ActivityBS(), array(
                "piva" => $piva,
            ), $id_activity);

            $id_activityaddress = DelegateUtility::getEntityIdByFields(new ActivityaddressBS(), array(
                "cod" => $cod_activityaddress,
            ), $id_activityaddress);

            parent::startTransaction();

            $activityaddressBS = new ActivityaddressBS();
            $activityaddressBS->resetPrincipal($id_activity, $this->groups, $this->likegroups);

            $activityaddressBS = new ActivityaddressBS();
            $activityaddressBS->updateField($id_activityaddress, "flgprincipal", 1);

            parent::commitTransaction();

            $this->ok(TranslatorUtility::__translate("INFO_ACTIVITYADDRESS_SET_PRINCIPAL", $this->localefile));
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_ACTIVITYADDRESS_SET_PRINCIPAL");
            return false;
        }
    }

    // ------------- RELATION
    function saveRelation($id_activity, $addressIn, $tpaddress, $flgprincipal = false) {
        $this->LOG_FUNCTION = "saveRelation";
        try {
            parent::startTransaction();

            $addressUI = new AddressUI();
            $addressUI->json = $this->json;
            $addressUI->transactional = true;
            $id_address = $addressUI->save($addressIn);
            if (empty($id_address)) {
                parent::rollbackTransaction();
                parent::mappingDelegate($addressUI);
                return 0;
            }

            $activityaddressBS = new ActivityaddressBS();
            $activityaddress = $activityaddressBS->instance();
            $activityaddress['Activityaddress']['cod'] = FileUtility::uuid_medium_unique();
            $activityaddress['Activityaddress']['activity'] = $id_activity;
            $activityaddress['Activityaddress']['address'] = $id_address;
            $activityaddress['Activityaddress']['tpaddress'] = $tpaddress;
            $id_activityaddress = $activityaddressBS->save($activityaddress);
            parent::saveInGroup($activityaddressBS, $id_activityaddress);

            if (!empty($id_activityaddress)) {
                if ($flgprincipal) {
                    $activityaddressBS = new ActivityaddressBS();
                    $activityaddressBS->resetPrincipal($id_activity);

                    $activityaddressBS = new ActivityaddressBS();
                    $activityaddressBS->updateField($id_activityaddress, "flgprincipal", 1);
                }
                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_ACTIVITYADDRESS_SAVE", $this->localefile));
                return $id_activityaddress;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::nonSalvato($this, "ERROR_ACTIVITYADDRESS_SAVE");
                return 0;
            }

        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_ACTIVITYADDRESS_SAVE");
            return 0;
        }
    }
}
