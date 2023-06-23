<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("EventBS", "modules/calendar/business");
App::uses("FileUtility", "modules/coreutils/utility");
App::uses("TypologicalUI", "modules/cakeutils/delegate");

class EventUI extends AppGenericUI {

    function __construct() {
        parent::__construct("EventUI");
        $this->localefile = "event";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("title", null, ""),
            new ObjPropertyEntity("description", null, ""),
            new ObjPropertyEntity("dtainit", null, ""),
            new ObjPropertyEntity("dtaend", null, ""),
            new ObjPropertyEntity("tpevent", null, 0),
            new ObjPropertyEntity("tpcat", null, 0),
            new ObjPropertyEntity("flgdeleted", null, 0),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_EVENT_NOT_FOUND");
                return "";
            }
            $eventBS = new EventBS();
            $eventBS->json = $this->json;
            parent::completeByJsonFkVf($eventBS);
            if (!empty($cod)) {
                $eventBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $eventBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_EVENT_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $eventBS = !empty($bs) ? $bs : new EventBS();
            $eventBS->json = $this->json;
            parent::completeByJsonFkVf($eventBS);
            parent::evalConditions($eventBS, $conditions);
            parent::evalOrders($eventBS, $orders);
            $events = $eventBS->table($conditions, $orders, $paginate);
            parent::evalPagination($eventBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($events);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($eventIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $event = DelegateUtility::getEntityToSave(new EventBS(), $eventIn, $this->obj);

            if (!empty($event)) {

                $eventBS = new EventBS();
                $id_event = $eventBS->save($event);
                parent::saveInGroup($eventBS, $id_event);

                parent::commitTransaction();
                if (!empty($id_event)) {
                    $this->ok(TranslatorUtility::__translate("INFO_EVENT_SAVE", $this->localefile));
                    return $id_event;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_EVENT_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_EVENT_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_EVENT_SAVE");
            return 0;
        }
    }

    function edit($id, $eventIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $event = DelegateUtility::getEntityToEdit(new EventBS(), $eventIn, $this->obj, $id);

            if (!empty($event)) {
                $eventBS = new EventBS();
                $id_event = $eventBS->save($event);
                parent::saveInGroup($eventBS, $id_event);
                parent::delInGroup($eventBS, $id_event);

                parent::commitTransaction();
                if (!empty($id_event)) {
                    $this->ok(TranslatorUtility::__translate("INFO_EVENT_EDIT", $this->localefile));
                    return $id_event;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_EVENT_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_EVENT_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_EVENT_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $eventBS = new EventBS();
                $eventBS->delete($id);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_EVENT_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_EVENT_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_EVENT_DELETE");
            return false;
        }
    }

    // ---------------- TYPOLOGICAL
    function tpevent($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "tpevent";
        try {
            $typologicalUI = new TypologicalUI("Tpevent", "calendar");
            $typologicalUI->json = $this->json;
            parent::assignToDelegate($typologicalUI);
            $result = $typologicalUI->table($conditions, $orders, $paginate, $bs);
            parent::mappingDelegate($typologicalUI);
            return $result;
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }
}
