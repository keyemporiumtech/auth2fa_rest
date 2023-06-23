<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("CityBS", "modules/localesystem/business");
App::uses("FileUtility", "modules/coreutils/utility");

class CityUI extends AppGenericUI {

    function __construct() {
        parent::__construct("CityUI");
        $this->localefile = "city";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("countrycode", null, ""),
            new ObjPropertyEntity("postalcode", null, ""),
            new ObjPropertyEntity("place", null, ""),
            new ObjPropertyEntity("region", null, ""),
            new ObjPropertyEntity("regioncode", null, ""),
            new ObjPropertyEntity("province", null, ""),
            new ObjPropertyEntity("provincecode", null, ""),
            new ObjPropertyEntity("community", null, ""),
            new ObjPropertyEntity("communitycode", null, ""),
            new ObjPropertyEntity("geo1", null, ""),
            new ObjPropertyEntity("geo2", null, ""),
            new ObjPropertyEntity("nation", null, 0),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_CITY_NOT_FOUND");
                return "";
            }
            $cityBS = new CityBS();
            $cityBS->json = $this->json;
            parent::completeByJsonFkVf($cityBS);
            if (!empty($cod)) {
                $cityBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $cityBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_CITY_NOT_FOUND");
            return "";
        }
    }

    function getByGeo($lat = null, $lon = null) {
        $this->LOG_FUNCTION = "getByGeo";
        try {
            if (empty($lat) || empty($lon)) {
                DelegateUtility::paramsNull($this, "ERROR_CITY_NOT_FOUND");
                return "";
            }
            $cityBS = new CityBS();
            $cityBS->json = $this->json;
            parent::completeByJsonFkVf($cityBS);
            $cityBS->addCondition("geo1", $lat);
            $cityBS->addCondition("geo2", $lon);

            $this->ok();
            return $cityBS->unique();
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_CITY_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $cityBS = !empty($bs) ? $bs : new CityBS();
            $cityBS->json = $this->json;
            parent::completeByJsonFkVf($cityBS);
            parent::evalConditions($cityBS, $conditions);
            parent::evalOrders($cityBS, $orders);
            $citys = $cityBS->table($conditions, $orders, $paginate);
            parent::evalPagination($cityBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($citys);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($cityIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $city = DelegateUtility::getEntityToSave(new CityBS(), $cityIn, $this->obj);

            if (!empty($city)) {

                $cityBS = new CityBS();
                $id_city = $cityBS->save($city);
                parent::saveInGroup($cityBS, $id_city);

                parent::commitTransaction();
                if (!empty($id_city)) {
                    $this->ok(TranslatorUtility::__translate("INFO_CITY_SAVE", $this->localefile));
                    return $id_city;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_CITY_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_CITY_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_CITY_SAVE");
            return 0;
        }
    }

    function edit($id, $cityIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $city = DelegateUtility::getEntityToEdit(new CityBS(), $cityIn, $this->obj, $id);

            if (!empty($city)) {
                $cityBS = new CityBS();
                $id_city = $cityBS->save($city);
                parent::saveInGroup($cityBS, $id_city);
                parent::delInGroup($cityBS, $id_city);

                parent::commitTransaction();
                if (!empty($id_city)) {
                    $this->ok(TranslatorUtility::__translate("INFO_CITY_EDIT", $this->localefile));
                    return $id_city;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_CITY_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_CITY_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_CITY_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $cityBS = new CityBS();
                $cityBS->delete($id);
                parent::delInGroup($cityBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_CITY_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_CITY_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_CITY_DELETE");
            return false;
        }
    }

    // OTHERS
    function regions($id_nation = null) {
        $this->LOG_FUNCTION = "regions";
        try {
            if (empty($id_nation)) {
                DelegateUtility::paramsNull($this, "ERROR_NO_DATA_FOUND");
                return "";
            }

            $cityBS = new CityBS();
            $cityBS->json = $this->json;
            $sql = "SELECT DISTINCT City.regioncode, City.region FROM cities as City WHERE City.nation=$id_nation ORDER BY region";

            $this->ok();
            return $cityBS->query($sql, false);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND");
            return "";
        }
    }

    function provinces($id_nation = null, $cod_region = null) {
        $this->LOG_FUNCTION = "provinces";
        try {
            if (empty($id_nation) || empty($cod_region)) {
                DelegateUtility::paramsNull($this, "ERROR_NO_DATA_FOUND");
                return "";
            }

            $cityBS = new CityBS();
            $cityBS->json = $this->json;
            $sql = "SELECT DISTINCT City.provincecode, City.province FROM cities as City WHERE City.nation=$id_nation AND City.regioncode='$cod_region' ORDER BY province";

            $this->ok();
            return $cityBS->query($sql, false);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND");
            return "";
        }
    }

    function communities($id_nation = null, $cod_region = null, $cod_province = null) {
        $this->LOG_FUNCTION = "communities";
        try {
            if (empty($id_nation) || empty($cod_region) || empty($cod_province)) {
                DelegateUtility::paramsNull($this, "ERROR_NO_DATA_FOUND");
                return "";
            }

            $cityBS = new CityBS();
            $cityBS->json = $this->json;
            $sql = "SELECT DISTINCT City.communitycode, City.community FROM cities as City WHERE City.nation=$id_nation AND City.regioncode='$cod_region' AND City.provincecode='$cod_region' ORDER BY community";

            $this->ok();
            return $cityBS->query($sql, false);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND");
            return "";
        }
    }
}
