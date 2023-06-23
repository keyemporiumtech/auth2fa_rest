<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("EventattachmentBS", "modules/calendar/business");
App::uses("FileUtility", "modules/coreutils/utility");

class EventattachmentUI extends AppGenericUI {

    function __construct() {
        parent::__construct("EventattachmentUI");
        $this->localefile = "eventattachment";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("flgprincipal", null, 0),
            new ObjPropertyEntity("attachment", null, 0),
            new ObjPropertyEntity("tpattachment", null, 0),
            new ObjPropertyEntity("event", null, 0),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_EVENTATTACHMENT_NOT_FOUND");
                return "";
            }
            $eventattachmentBS = new EventattachmentBS();
            $eventattachmentBS->json = $this->json;
            parent::completeByJsonFkVf($eventattachmentBS);
            if (!empty($cod)) {
                $eventattachmentBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $eventattachmentBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_EVENTATTACHMENT_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $eventattachmentBS = !empty($bs) ? $bs : new EventattachmentBS();
            $eventattachmentBS->json = $this->json;
            parent::completeByJsonFkVf($eventattachmentBS);
            parent::evalConditions($eventattachmentBS, $conditions);
            parent::evalOrders($eventattachmentBS, $orders);
            $eventattachments = $eventattachmentBS->table($conditions, $orders, $paginate);
            parent::evalPagination($eventattachmentBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($eventattachments);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($eventattachmentIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $eventattachment = DelegateUtility::getEntityToSave(new EventattachmentBS(), $eventattachmentIn, $this->obj);

            if (!empty($eventattachment)) {

                $eventattachmentBS = new EventattachmentBS();
                $id_eventattachment = $eventattachmentBS->save($eventattachment);
                parent::saveInGroup($eventattachmentBS, $id_eventattachment);

                parent::commitTransaction();
                if (!empty($id_eventattachment)) {
                    $this->ok(TranslatorUtility::__translate("INFO_EVENTATTACHMENT_SAVE", $this->localefile));
                    return $id_eventattachment;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_EVENTATTACHMENT_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_EVENTATTACHMENT_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_EVENTATTACHMENT_SAVE");
            return 0;
        }
    }

    function edit($id, $eventattachmentIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $eventattachment = DelegateUtility::getEntityToEdit(new EventattachmentBS(), $eventattachmentIn, $this->obj, $id);

            if (!empty($eventattachment)) {
                $eventattachmentBS = new EventattachmentBS();
                $id_eventattachment = $eventattachmentBS->save($eventattachment);
                parent::saveInGroup($eventattachmentBS, $id_eventattachment);
                parent::delInGroup($eventattachmentBS, $id_eventattachment);

                parent::commitTransaction();
                if (!empty($id_eventattachment)) {
                    $this->ok(TranslatorUtility::__translate("INFO_EVENTATTACHMENT_EDIT", $this->localefile));
                    return $id_eventattachment;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_EVENTATTACHMENT_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_EVENTATTACHMENT_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_EVENTATTACHMENT_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $eventattachmentBS = new EventattachmentBS();
                $eventattachmentBS->delete($id);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_EVENTATTACHMENT_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_EVENTATTACHMENT_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_EVENTATTACHMENT_DELETE");
            return false;
        }
    }

    // ------------- PRINCIPAL
    function getPrincipal($id_event = null, $cod = null, $type = null) {
        $this->LOG_FUNCTION = "getPrincipal";
        try {
            if (empty($id_event) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_EVENTATTACHMENT_NOT_FOUND");
                return "";
            }
            if (empty($type)) {
                DelegateUtility::paramsNull($this, "ERROR_EVENTATTACHMENT_NOT_FOUND");
                return "";
            }
            $id_event = DelegateUtility::getEntityIdByFields(new UserBS(), array(
                "cod" => $cod,
            ), $id_event);
            $eventattachmentBS = new EventattachmentBS();
            $eventattachmentBS->json = $this->json;
            parent::completeByJsonFkVf($eventattachmentBS);

            $belongs = DelegateUtility::getObjList($this->json, $this->belongs, true);
            if (ArrayUtility::contains($belongs, "attachment_fk")) {
                DelegateUtility::excludeFieldsByQuery($eventattachmentBS, array("tpattachment"));
                $eventattachmentBS->addCondition("attachment_fk.tpattachment", $type);
            } else {
                $eventattachmentBS->addCondition("tpattachment", $type);
            }

            $eventattachmentBS->addCondition("event", $id_event);
            $eventattachmentBS->addCondition("flgprincipal", 1);

            $this->ok();
            return $eventattachmentBS->unique();
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_EVENTATTACHMENT_NOT_FOUND");
            return "";
        }
    }

    function setPrincipal($id_event = null, $cod = null, $id_eventattachment = null, $cod_eventattachment = null, $type = null) {
        $this->LOG_FUNCTION = "setPrincipal";
        try {
            if ((empty($id_event) && empty($cod)) || (empty($id_eventattachment) && empty($cod_eventattachment))) {
                DelegateUtility::paramsNull($this, "ERROR_EVENTATTACHMENT_SET_PRINCIPAL");
                return false;
            }
            if (empty($type)) {
                DelegateUtility::paramsNull($this, "ERROR_EVENTATTACHMENT_NOT_FOUND");
                return false;
            }
            $id_event = DelegateUtility::getEntityIdByFields(new UserBS(), array(
                "cod" => $cod,
            ), $id_event);

            $id_eventattachment = DelegateUtility::getEntityIdByFields(new EventattachmentBS(), array(
                "cod" => $cod_eventattachment,
                "tpattachment" => $type,
            ), $id_eventattachment);

            parent::startTransaction();

            $eventattachmentBS = new EventattachmentBS();
            $eventattachmentBS->resetPrincipal($id_event, $type, $this->groups, $this->likegroups, $this->json);

            $eventattachmentBS = new EventattachmentBS();
            $eventattachmentBS->updateField($id_eventattachment, "flgprincipal", 1);

            parent::commitTransaction();

            $this->ok(TranslatorUtility::__translate("INFO_EVENTATTACHMENT_SET_PRINCIPAL", $this->localefile));
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_EVENTATTACHMENT_SET_PRINCIPAL");
            return false;
        }
    }

    // ------------- RELATION
    function saveRelation($id_event, $attachmentIn, $tpattachment, $flgprincipal = false) {
        $this->LOG_FUNCTION = "saveRelation";
        try {
            parent::startTransaction();

            $attachmentUI = new AttachmentUI();
            $attachmentUI->json = $this->json;
            $attachmentUI->transactional = true;
            $id_attachment = $attachmentUI->save($attachmentIn);
            if (empty($id_attachment)) {
                parent::rollbackTransaction();
                parent::mappingDelegate($attachmentUI);
                return 0;
            }

            $eventattachmentBS = new EventattachmentBS();
            $eventattachment = $eventattachmentBS->instance();
            $eventattachment['Eventattachment']['cod'] = FileUtility::uuid_medium_unique();
            $eventattachment['Eventattachment']['event'] = $id_event;
            $eventattachment['Eventattachment']['attachment'] = $id_attachment;
            $eventattachment['Eventattachment']['tpattachment'] = $tpattachment;
            $id_eventattachment = $eventattachmentBS->save($eventattachment);
            parent::saveInGroup($eventattachmentBS, $id_eventattachment);

            if (!empty($id_eventattachment)) {
                if ($flgprincipal) {
                    $eventattachmentBS = new EventattachmentBS();
                    $eventattachmentBS->resetPrincipal($id_event, $tpattachment);

                    $eventattachmentBS = new EventattachmentBS();
                    $eventattachmentBS->updateField($id_eventattachment, "flgprincipal", 1);
                }
                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_EVENTATTACHMENT_SAVE", $this->localefile));
                return $id_eventattachment;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::nonSalvato($this, "ERROR_EVENTATTACHMENT_SAVE");
                return 0;
            }

        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_EVENTATTACHMENT_SAVE");
            return 0;
        }
    }
}
