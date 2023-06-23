<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("ServiceattachmentBS", "modules/shop_warehouse/business");
App::uses("FileUtility", "modules/coreutils/utility");

class ServiceattachmentUI extends AppGenericUI {

    function __construct() {
        parent::__construct("ServiceattachmentUI");
        $this->localefile = "serviceattachment";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("service", null, 0),
            new ObjPropertyEntity("attachment", null, 0),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_SERVICEATTACHMENT_NOT_FOUND");
                return "";
            }
            $serviceattachmentBS = new ServiceattachmentBS();
            $serviceattachmentBS->json = $this->json;
            parent::completeByJsonFkVf($serviceattachmentBS);
            if (!empty($cod)) {
                $serviceattachmentBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $serviceattachmentBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_SERVICEATTACHMENT_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $serviceattachmentBS = !empty($bs) ? $bs : new ServiceattachmentBS();
            $serviceattachmentBS->json = $this->json;
            parent::completeByJsonFkVf($serviceattachmentBS);
            parent::evalConditions($serviceattachmentBS, $conditions);
            parent::evalOrders($serviceattachmentBS, $orders);
            $serviceattachments = $serviceattachmentBS->table($conditions, $orders, $paginate);
            parent::evalPagination($serviceattachmentBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($serviceattachments);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($serviceattachmentIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $serviceattachment = DelegateUtility::getEntityToSave(new ServiceattachmentBS(), $serviceattachmentIn, $this->obj);

            if (!empty($serviceattachment)) {

                $serviceattachmentBS = new ServiceattachmentBS();
                $id_serviceattachment = $serviceattachmentBS->save($serviceattachment);
                parent::saveInGroup($serviceattachmentBS, $id_serviceattachment);

                parent::commitTransaction();
                if (!empty($id_serviceattachment)) {
                    $this->ok(TranslatorUtility::__translate("INFO_SERVICEATTACHMENT_SAVE", $this->localefile));
                    return $id_serviceattachment;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_SERVICEATTACHMENT_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_SERVICEATTACHMENT_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_SERVICEATTACHMENT_SAVE");
            return 0;
        }
    }

    function edit($id, $serviceattachmentIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $serviceattachment = DelegateUtility::getEntityToEdit(new ServiceattachmentBS(), $serviceattachmentIn, $this->obj, $id);

            if (!empty($serviceattachment)) {
                $serviceattachmentBS = new ServiceattachmentBS();
                $id_serviceattachment = $serviceattachmentBS->save($serviceattachment);
                parent::saveInGroup($serviceattachmentBS, $id_serviceattachment);
                parent::delInGroup($serviceattachmentBS, $id_serviceattachment);

                parent::commitTransaction();
                if (!empty($id_serviceattachment)) {
                    $this->ok(TranslatorUtility::__translate("INFO_SERVICEATTACHMENT_EDIT", $this->localefile));
                    return $id_serviceattachment;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_SERVICEATTACHMENT_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_SERVICEATTACHMENT_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_SERVICEATTACHMENT_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $serviceattachmentBS = new ServiceattachmentBS();
                $serviceattachmentBS->delete($id);
                parent::delInGroup($serviceattachmentBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_SERVICEATTACHMENT_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_SERVICEATTACHMENT_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_SERVICEATTACHMENT_DELETE");
            return false;
        }
    }
}
