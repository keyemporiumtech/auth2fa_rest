<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("NationBS", "modules/localesystem/business");
App::uses("FileUtility", "modules/coreutils/utility");

class NationUI extends AppGenericUI {

    function __construct() {
        parent::__construct("NationUI");
        $this->localefile = "nation";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("name", null, ""),
            new ObjPropertyEntity("description", null, ""),
            new ObjPropertyEntity("capital", null, ""),
            new ObjPropertyEntity("continent", null, ""),
            new ObjPropertyEntity("currencycod", null, ""),
            new ObjPropertyEntity("tld", null, ""),
            new ObjPropertyEntity("type", null, ""),
            new ObjPropertyEntity("cod_iso3166", null, ""),
            new ObjPropertyEntity("geo1", null, ""),
            new ObjPropertyEntity("geo2", null, ""),
            new ObjPropertyEntity("tel", null, ""),
            new ObjPropertyEntity("flgiban", null, 0),
            new ObjPropertyEntity("flgused", null, 1),
            new ObjPropertyEntity("symbol", null, ""),
        );
    }

    function get($id = null, $cod = null, $iso = null, $symbol = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod) && empty($iso) && empty($symbol)) {
                DelegateUtility::paramsNull($this, "ERROR_NATION_NOT_FOUND");
                return "";
            }
            $nationBS = new NationBS();
            $nationBS->json = $this->json;
            parent::completeByJsonFkVf($nationBS);
            if (!empty($cod)) {
                $nationBS->addCondition("cod", $cod);
            }
            if (!empty($iso)) {
                $nationBS->addCondition("cod_iso3166", $iso);
            }
            if (!empty($symbol)) {
                $nationBS->addCondition("symbol", $symbol);
            }
            $this->ok();
            return $nationBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NATION_NOT_FOUND");
            return "";
        }
    }

    function getByGeo($lat = null, $lon = null) {
        $this->LOG_FUNCTION = "getByGeo";
        try {
            if (empty($lat) || empty($lon)) {
                DelegateUtility::paramsNull($this, "ERROR_NATION_NOT_FOUND");
                return "";
            }
            $nationBS = new NationBS();
            $nationBS->json = $this->json;
            parent::completeByJsonFkVf($nationBS);
            $nationBS->addCondition("geo1", $lat);
            $nationBS->addCondition("geo2", $lon);

            $this->ok();
            return $nationBS->unique();
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NATION_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $nationBS = !empty($bs) ? $bs : new NationBS();
            $nationBS->json = $this->json;
            parent::completeByJsonFkVf($nationBS);
            parent::evalConditions($nationBS, $conditions);
            parent::evalOrders($nationBS, $orders);
            $nations = $nationBS->table($conditions, $orders, $paginate);
            parent::evalPagination($nationBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($nations);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($nationIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $nation = DelegateUtility::getEntityToSave(new NationBS(), $nationIn, $this->obj);

            if (!empty($nation)) {

                $nationBS = new NationBS();
                $id_nation = $nationBS->save($nation);
                parent::saveInGroup($nationBS, $id_nation);

                parent::commitTransaction();
                if (!empty($id_nation)) {
                    $this->ok(TranslatorUtility::__translate("INFO_NATION_SAVE", $this->localefile));
                    return $id_nation;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_NATION_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_NATION_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_NATION_SAVE");
            return 0;
        }
    }

    function edit($id, $nationIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $nation = DelegateUtility::getEntityToEdit(new NationBS(), $nationIn, $this->obj, $id);

            if (!empty($nation)) {
                $nationBS = new NationBS();
                $id_nation = $nationBS->save($nation);
                parent::saveInGroup($nationBS, $id_nation);
                parent::delInGroup($nationBS, $id_nation);

                parent::commitTransaction();
                if (!empty($id_nation)) {
                    $this->ok(TranslatorUtility::__translate("INFO_NATION_EDIT", $this->localefile));
                    return $id_nation;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_NATION_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_NATION_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_NATION_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $nationBS = new NationBS();
                $nationBS->delete($id);
                parent::delInGroup($nationBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_NATION_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_NATION_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_NATION_DELETE");
            return false;
        }
    }
}
