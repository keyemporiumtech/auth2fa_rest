<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("EventreferenceBS", "modules/calendar/business");
App::uses("FileUtility", "modules/coreutils/utility");

class EventreferenceUI extends AppGenericUI {

    function __construct() {
        parent::__construct("EventreferenceUI");
        $this->localefile = "eventreference";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("flgprincipal", null, 0),
            new ObjPropertyEntity("contactreference", null, 0),
            new ObjPropertyEntity("tpcontactreference", null, 0),
            new ObjPropertyEntity("event", null, 0),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_EVENTREFERENCE_NOT_FOUND");
                return "";
            }
            $eventreferenceBS = new EventreferenceBS();
            $eventreferenceBS->json = $this->json;
            parent::completeByJsonFkVf($eventreferenceBS);
            if (!empty($cod)) {
                $eventreferenceBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $eventreferenceBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_EVENTREFERENCE_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $eventreferenceBS = !empty($bs) ? $bs : new EventreferenceBS();
            $eventreferenceBS->json = $this->json;
            parent::completeByJsonFkVf($eventreferenceBS);
            parent::evalConditions($eventreferenceBS, $conditions);
            parent::evalOrders($eventreferenceBS, $orders);
            $eventreferences = $eventreferenceBS->table($conditions, $orders, $paginate);
            parent::evalPagination($eventreferenceBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($eventreferences);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($eventreferenceIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $eventreference = DelegateUtility::getEntityToSave(new EventreferenceBS(), $eventreferenceIn, $this->obj);

            if (!empty($eventreference)) {

                $eventreferenceBS = new EventreferenceBS();
                $id_eventreference = $eventreferenceBS->save($eventreference);
                parent::saveInGroup($eventreferenceBS, $id_eventreference);

                parent::commitTransaction();
                if (!empty($id_eventreference)) {
                    $this->ok(TranslatorUtility::__translate("INFO_EVENTREFERENCE_SAVE", $this->localefile));
                    return $id_eventreference;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_EVENTREFERENCE_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_EVENTREFERENCE_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_EVENTREFERENCE_SAVE");
            return 0;
        }
    }

    function edit($id, $eventreferenceIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $eventreference = DelegateUtility::getEntityToEdit(new EventreferenceBS(), $eventreferenceIn, $this->obj, $id);

            if (!empty($eventreference)) {
                $eventreferenceBS = new EventreferenceBS();
                $id_eventreference = $eventreferenceBS->save($eventreference);
                parent::saveInGroup($eventreferenceBS, $id_eventreference);
                parent::delInGroup($eventreferenceBS, $id_eventreference);

                parent::commitTransaction();
                if (!empty($id_eventreference)) {
                    $this->ok(TranslatorUtility::__translate("INFO_EVENTREFERENCE_EDIT", $this->localefile));
                    return $id_eventreference;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_EVENTREFERENCE_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_EVENTREFERENCE_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_EVENTREFERENCE_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $eventreferenceBS = new EventreferenceBS();
                $eventreferenceBS->delete($id);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_EVENTREFERENCE_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_EVENTREFERENCE_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_EVENTREFERENCE_DELETE");
            return false;
        }
    }

    // ------------- PRINCIPAL
    function getPrincipal($id_event = null, $cod = null, $type = null) {
        $this->LOG_FUNCTION = "getPrincipal";
        try {
            if (empty($id_event) && empty($username)) {
                DelegateUtility::paramsNull($this, "ERROR_EVENTREFERENCE_NOT_FOUND");
                return "";
            }
            if (empty($type)) {
                DelegateUtility::paramsNull($this, "ERROR_EVENTREFERENCE_NOT_FOUND");
                return "";
            }
            $id_event = DelegateUtility::getEntityIdByFields(new EventBS(), array(
                "cod" => $cod,
            ), $id_event);
            $eventreferenceBS = new EventreferenceBS();
            $eventreferenceBS->json = $this->json;
            parent::completeByJsonFkVf($eventreferenceBS);

            $belongs = DelegateUtility::getObjList($this->json, $this->belongs, true);
            if (ArrayUtility::contains($belongs, "contactreference_fk")) {
                DelegateUtility::excludeFieldsByQuery($eventreferenceBS, array("tpcontactreference"));
                $eventreferenceBS->addCondition("contactreference_fk.tpcontactreference", $type);
            } else {
                $eventreferenceBS->addCondition("tpcontactreference", $type);
            }

            $eventreferenceBS->addCondition("event", $id_event);
            $eventreferenceBS->addCondition("flgprincipal", 1);

            $this->ok();
            return $eventreferenceBS->unique();
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_EVENTREFERENCE_NOT_FOUND");
            return "";
        }
    }

    function setPrincipal($id_event = null, $cod = null, $id_eventreference = null, $cod_eventreference = null, $type = null) {
        $this->LOG_FUNCTION = "setPrincipal";
        try {
            if ((empty($id_event) && empty($cod)) || (empty($id_eventreference) && empty($cod_eventreference))) {
                DelegateUtility::paramsNull($this, "ERROR_EVENTREFERENCE_SET_PRINCIPAL");
                return false;
            }
            if (empty($type)) {
                DelegateUtility::paramsNull($this, "ERROR_EVENTREFERENCE_NOT_FOUND");
                return "";
            }
            $id_event = DelegateUtility::getEntityIdByFields(new EventBS(), array(
                "cod" => $cod,
            ), $id_event);

            $id_eventreference = DelegateUtility::getEntityIdByFields(new EventreferenceBS(), array(
                "cod" => $cod_eventreference,
                "tpcontactreference" => $type,
            ), $id_eventreference);

            parent::startTransaction();

            $eventreferenceBS = new EventreferenceBS();
            $eventreferenceBS->resetPrincipal($id_event, $type, $this->groups, $this->likegroups, $this->json);

            $eventreferenceBS = new EventreferenceBS();
            $eventreferenceBS->updateField($id_eventreference, "flgprincipal", 1);

            parent::commitTransaction();

            $this->ok(TranslatorUtility::__translate("INFO_EVENTREFERENCE_SET_PRINCIPAL", $this->localefile));
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_EVENTREFERENCE_SET_PRINCIPAL");
            return false;
        }
    }

    // ------------- RELATION
    function saveRelation($id_event, $contactreferenceIn, $tpcontactreference, $flgprincipal = false) {
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

            $usercontactreferenceBS = new EventreferenceBS();
            $usercontactreference = $usercontactreferenceBS->instance();
            $usercontactreference['Eventreference']['cod'] = FileUtility::uuid_medium_unique();
            $usercontactreference['Eventreference']['event'] = $id_event;
            $usercontactreference['Eventreference']['contactreference'] = $id_contactreference;
            $usercontactreference['Eventreference']['tpcontactreference'] = $tpcontactreference;
            $id_usercontactreference = $usercontactreferenceBS->save($usercontactreference);
            parent::saveInGroup($usercontactreferenceBS, $id_usercontactreference);

            if (!empty($id_usercontactreference)) {
                if ($flgprincipal) {
                    $eventreferenceBS = new EventreferenceBS();
                    $eventreferenceBS->resetPrincipal($id_event, $tpcontactreference);

                    $eventreferenceBS = new EventreferenceBS();
                    $eventreferenceBS->updateField($id_usercontactreference, "flgprincipal", 1);
                }
                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_EVENTREFERENCE_SAVE", $this->localefile));
                return $id_usercontactreference;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::nonSalvato($this, "ERROR_EVENTREFERENCE_SAVE");
                return 0;
            }

        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_EVENTREFERENCE_SAVE");
            return 0;
        }
    }
}
