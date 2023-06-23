<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("ServicereservesettingBS", "modules/shop_warehouse/business");
App::uses("FileUtility", "modules/coreutils/utility");

class ServicereservesettingUI extends AppGenericUI {

    function __construct() {
        parent::__construct("ServicereservesettingUI");
        $this->localefile = "servicereservesetting";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("service", null, 0),
            new ObjPropertyEntity("settings", null, 0),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_SERVICERESERVATIONSETTING_NOT_FOUND");
                return "";
            }
            $servicereservesettingBS = new ServicereservesettingBS();
            $servicereservesettingBS->json = $this->json;
            parent::completeByJsonFkVf($servicereservesettingBS);
            if (!empty($cod)) {
                $servicereservesettingBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $servicereservesettingBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_SERVICERESERVATIONSETTING_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $servicereservesettingBS = !empty($bs) ? $bs : new ServicereservesettingBS();
            $servicereservesettingBS->json = $this->json;
            parent::completeByJsonFkVf($servicereservesettingBS);
            parent::evalConditions($servicereservesettingBS, $conditions);
            parent::evalOrders($servicereservesettingBS, $orders);
            $servicereservesettings = $servicereservesettingBS->table($conditions, $orders, $paginate);
            parent::evalPagination($servicereservesettingBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($servicereservesettings);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($servicereservesettingIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $servicereservesetting = DelegateUtility::getEntityToSave(new ServicereservesettingBS(), $servicereservesettingIn, $this->obj);

            if (!empty($servicereservesetting)) {

                $servicereservesettingBS = new ServicereservesettingBS();
                $id_servicereservesetting = $servicereservesettingBS->save($servicereservesetting);
                parent::saveInGroup($servicereservesettingBS, $id_servicereservesetting);

                parent::commitTransaction();
                if (!empty($id_servicereservesetting)) {
                    $this->ok(TranslatorUtility::__translate("INFO_SERVICERESERVATIONSETTING_SAVE", $this->localefile));
                    return $id_servicereservesetting;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_SERVICERESERVATIONSETTING_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_SERVICERESERVATIONSETTING_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_SERVICERESERVATIONSETTING_SAVE");
            return 0;
        }
    }

    function edit($id, $servicereservesettingIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $servicereservesetting = DelegateUtility::getEntityToEdit(new ServicereservesettingBS(), $servicereservesettingIn, $this->obj, $id);

            if (!empty($servicereservesetting)) {
                $servicereservesettingBS = new ServicereservesettingBS();
                $id_servicereservesetting = $servicereservesettingBS->save($servicereservesetting);
                parent::saveInGroup($servicereservesettingBS, $id_servicereservesetting);
                parent::delInGroup($servicereservesettingBS, $id_servicereservesetting);

                parent::commitTransaction();
                if (!empty($id_servicereservesetting)) {
                    $this->ok(TranslatorUtility::__translate("INFO_SERVICERESERVATIONSETTING_EDIT", $this->localefile));
                    return $id_servicereservesetting;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_SERVICERESERVATIONSETTING_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_SERVICERESERVATIONSETTING_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_SERVICERESERVATIONSETTING_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $servicereservesettingBS = new ServicereservesettingBS();
                $servicereservesettingBS->delete($id);
                parent::delInGroup($servicereservesettingBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_SERVICERESERVATIONSETTING_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_SERVICERESERVATIONSETTING_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_SERVICERESERVATIONSETTING_DELETE");
            return false;
        }
    }
}
