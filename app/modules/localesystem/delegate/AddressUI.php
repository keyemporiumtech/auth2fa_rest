<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("AddressBS", "modules/localesystem/business");
App::uses("FileUtility", "modules/coreutils/utility");
App::uses("TypologicalUI", "modules/cakeutils/delegate");

class AddressUI extends AppGenericUI {

    function __construct() {
        parent::__construct("AddressUI");
        $this->localefile = "address";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid()),
            new ObjPropertyEntity("street", null, ""),
            new ObjPropertyEntity("number", null, ""),
            new ObjPropertyEntity("zip", null, ""),
            new ObjPropertyEntity("city", null, ""),
            new ObjPropertyEntity("province", null, ""),
            new ObjPropertyEntity("region", null, ""),
            new ObjPropertyEntity("geo1", null, ""),
            new ObjPropertyEntity("geo2", null, ""),
            new ObjPropertyEntity("nation", null, 0),
            new ObjPropertyEntity("cityid", null, 0),
            new ObjPropertyEntity("tpaddress", null, 0),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_ADDRESS_NOT_FOUND");
                return "";
            }
            $addressBS = new AddressBS();
            $addressBS->json = $this->json;
            parent::completeByJsonFkVf($addressBS);
            if (!empty($cod)) {
                $addressBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $addressBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_ADDRESS_NOT_FOUND");
            return "";
        }
    }

    function getByStreet($street = null, $number = null) {
        $this->LOG_FUNCTION = "getByStreet";
        try {
            if (empty($street) || empty($number)) {
                DelegateUtility::paramsNull($this, "ERROR_ADDRESS_NOT_FOUND");
                return "";
            }
            $addressBS = new AddressBS();
            $addressBS->json = $this->json;
            parent::completeByJsonFkVf($addressBS);
            $addressBS->addCondition("street", $street);
            $addressBS->addCondition("number", $number);

            $this->ok();
            return $addressBS->unique();
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_ADDRESS_NOT_FOUND");
            return "";
        }
    }

    function getByGeo($lat = null, $lon = null) {
        $this->LOG_FUNCTION = "getByGeo";
        try {
            if (empty($lat) || empty($lon)) {
                DelegateUtility::paramsNull($this, "ERROR_ADDRESS_NOT_FOUND");
                return "";
            }
            $addressBS = new AddressBS();
            $addressBS->json = $this->json;
            parent::completeByJsonFkVf($addressBS);
            $addressBS->addCondition("geo1", $lat);
            $addressBS->addCondition("geo2", $lon);

            $this->ok();
            return $addressBS->unique();
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_ADDRESS_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $addressBS = !empty($bs) ? $bs : new AddressBS();
            $addressBS->json = $this->json;
            parent::completeByJsonFkVf($addressBS);
            parent::evalConditions($addressBS, $conditions);
            parent::evalOrders($addressBS, $orders);
            $address = $addressBS->table($conditions, $orders, $paginate);
            parent::evalPagination($addressBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($address);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($addressIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $address = DelegateUtility::getEntityToSave(new AddressBS(), $addressIn, $this->obj);

            if (!empty($address)) {

                $addressBS = new AddressBS();
                $id_address = $addressBS->save($address);
                parent::saveInGroup($addressBS, $id_address);

                parent::commitTransaction();
                if (!empty($id_address)) {
                    $this->ok(TranslatorUtility::__translate("INFO_ADDRESS_SAVE", $this->localefile));
                    return $id_address;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_ADDRESS_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_ADDRESS_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_ADDRESS_SAVE");
            return 0;
        }
    }

    function edit($id, $addressIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $address = DelegateUtility::getEntityToEdit(new AddressBS(), $addressIn, $this->obj, $id);

            if (!empty($address)) {
                $addressBS = new AddressBS();
                $id_address = $addressBS->save($address);
                parent::saveInGroup($addressBS, $id_address);
                parent::delInGroup($addressBS, $id_address);

                parent::commitTransaction();
                if (!empty($id_address)) {
                    $this->ok(TranslatorUtility::__translate("INFO_ADDRESS_EDIT", $this->localefile));
                    return $id_address;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_ADDRESS_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_ADDRESS_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_ADDRESS_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $addressBS = new AddressBS();
                $addressBS->delete($id);
                parent::delInGroup($addressBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_ADDRESS_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_ADDRESS_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_ADDRESS_DELETE");
            return false;
        }
    }

    // ---------------- TYPOLOGICAL
    function tpaddress($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "tpaddress";
        try {
            $typologicalUI = new TypologicalUI("Tpaddress", "localesystem");
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
