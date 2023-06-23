<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("ReservationsettingBS", "modules/shop_warehouse/business");
App::uses("FileUtility", "modules/coreutils/utility");

class ReservationsettingUI extends AppGenericUI {

    function __construct() {
        parent::__construct("ReservationsettingUI");
        $this->localefile = "reservationsetting";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("dailyweeks", null, ""),
            new ObjPropertyEntity("dailymonths", null, ""),
            new ObjPropertyEntity("hhreservefrom", null, ""),
            new ObjPropertyEntity("hhreserveto", null, ""),
            new ObjPropertyEntity("dtafrom", null, ""),
            new ObjPropertyEntity("dtato", null, ""),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_RESERVATIONSETTING_NOT_FOUND");
                return "";
            }
            $reservationsettingBS = new ReservationsettingBS();
            $reservationsettingBS->json = $this->json;
            parent::completeByJsonFkVf($reservationsettingBS);
            if (!empty($cod)) {
                $reservationsettingBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $reservationsettingBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_RESERVATIONSETTING_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $reservationsettingBS = !empty($bs) ? $bs : new ReservationsettingBS();
            $reservationsettingBS->json = $this->json;
            parent::completeByJsonFkVf($reservationsettingBS);
            parent::evalConditions($reservationsettingBS, $conditions);
            parent::evalOrders($reservationsettingBS, $orders);
            $reservationsettings = $reservationsettingBS->table($conditions, $orders, $paginate);
            parent::evalPagination($reservationsettingBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($reservationsettings);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($reservationsettingIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $reservationsetting = DelegateUtility::getEntityToSave(new ReservationsettingBS(), $reservationsettingIn, $this->obj);

            if (!empty($reservationsetting)) {

                $reservationsettingBS = new ReservationsettingBS();
                $id_reservationsetting = $reservationsettingBS->save($reservationsetting);
                parent::saveInGroup($reservationsettingBS, $id_reservationsetting);

                parent::commitTransaction();
                if (!empty($id_reservationsetting)) {
                    $this->ok(TranslatorUtility::__translate("INFO_RESERVATIONSETTING_SAVE", $this->localefile));
                    return $id_reservationsetting;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_RESERVATIONSETTING_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_RESERVATIONSETTING_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_RESERVATIONSETTING_SAVE");
            return 0;
        }
    }

    function edit($id, $reservationsettingIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $reservationsetting = DelegateUtility::getEntityToEdit(new ReservationsettingBS(), $reservationsettingIn, $this->obj, $id);

            if (!empty($reservationsetting)) {
                $reservationsettingBS = new ReservationsettingBS();
                $id_reservationsetting = $reservationsettingBS->save($reservationsetting);
                parent::saveInGroup($reservationsettingBS, $id_reservationsetting);
                parent::delInGroup($reservationsettingBS, $id_reservationsetting);

                parent::commitTransaction();
                if (!empty($id_reservationsetting)) {
                    $this->ok(TranslatorUtility::__translate("INFO_RESERVATIONSETTING_EDIT", $this->localefile));
                    return $id_reservationsetting;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_RESERVATIONSETTING_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_RESERVATIONSETTING_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_RESERVATIONSETTING_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $reservationsettingBS = new ReservationsettingBS();
                $reservationsettingBS->delete($id);
                parent::delInGroup($reservationsettingBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_RESERVATIONSETTING_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_RESERVATIONSETTING_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_RESERVATIONSETTING_DELETE");
            return false;
        }
    }
}
