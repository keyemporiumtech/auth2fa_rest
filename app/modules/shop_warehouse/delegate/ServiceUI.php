<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("ServiceBS", "modules/shop_warehouse/business");
App::uses("FileUtility", "modules/coreutils/utility");
App::uses("DiscountBS", "modules/shop_warehouse/business");
App::uses("ServicediscountBS", "modules/shop_warehouse/business");
App::uses("PriceBS", "modules/shop_warehouse/business");
App::uses("ServicetaxBS", "modules/shop_warehouse/business");
App::uses("ServicetaxUI", "modules/shop_warehouse/delegate");

class ServiceUI extends AppGenericUI {

    function __construct() {
        parent::__construct("ServiceUI");
        $this->localefile = "service";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("name", null, ""),
            new ObjPropertyEntity("description", null, ""),
            new ObjPropertyEntity("image", null, 0),
            new ObjPropertyEntity("category", null, 0),
            new ObjPropertyEntity("price", null, 0),
            new ObjPropertyEntity("note", null, ""),
            new ObjPropertyEntity("flgdeleted", null, 0),
            new ObjPropertyEntity("flgreserve", null, 0),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_SERVICE_NOT_FOUND");
                return "";
            }
            $serviceBS = new ServiceBS();
            $serviceBS->json = $this->json;
            parent::completeByJsonFkVf($serviceBS);
            if (!empty($cod)) {
                $serviceBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $serviceBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_SERVICE_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $serviceBS = !empty($bs) ? $bs : new ServiceBS();
            $serviceBS->json = $this->json;
            parent::completeByJsonFkVf($serviceBS);
            parent::evalConditions($serviceBS, $conditions);
            parent::evalOrders($serviceBS, $orders);
            $services = $serviceBS->table($conditions, $orders, $paginate);
            parent::evalPagination($serviceBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($services);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($serviceIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $service = DelegateUtility::getEntityToSave(new ServiceBS(), $serviceIn, $this->obj);

            if (!empty($service)) {

                $serviceBS = new ServiceBS();
                $id_service = $serviceBS->save($service);
                parent::saveInGroup($serviceBS, $id_service);

                parent::commitTransaction();
                if (!empty($id_service)) {
                    $this->ok(TranslatorUtility::__translate("INFO_SERVICE_SAVE", $this->localefile));
                    return $id_service;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_SERVICE_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_SERVICE_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_SERVICE_SAVE");
            return 0;
        }
    }

    function edit($id, $serviceIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $service = DelegateUtility::getEntityToEdit(new ServiceBS(), $serviceIn, $this->obj, $id);

            if (!empty($service)) {
                $serviceBS = new ServiceBS();
                $id_service = $serviceBS->save($service);
                parent::saveInGroup($serviceBS, $id_service);
                parent::delInGroup($serviceBS, $id_service);

                parent::commitTransaction();
                if (!empty($id_service)) {
                    $this->ok(TranslatorUtility::__translate("INFO_SERVICE_EDIT", $this->localefile));
                    return $id_service;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_SERVICE_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_SERVICE_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_SERVICE_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $serviceBS = new ServiceBS();
                $serviceBS->delete($id);
                parent::delInGroup($serviceBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_SERVICE_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_SERVICE_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_SERVICE_DELETE");
            return false;
        }
    }

    // ------------------ PRICE
    function addPrice($priceIn, $id) {
        $this->LOG_FUNCTION = "addPrice";
        try {
            if (empty($priceIn) || empty($id)) {
                DelegateUtility::paramsNull($this, "ERROR_SERVICE_EDIT");
                return false;
            }
            parent::startTransaction();

            DelegatePriceUtility::addPrice($priceIn, $id, "Service");

            parent::commitTransaction();
            $this->ok(TranslatorUtility::__translate("INFO_SERVICE_EDIT", $this->localefile));
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_SERVICE_EDIT");
            return false;
        }
    }

    function editPrice($priceIn, $id) {
        $this->LOG_FUNCTION = "editPrice";
        try {
            if (empty($priceIn) || empty($id)) {
                DelegateUtility::paramsNull($this, "ERROR_SERVICE_EDIT");
                return false;
            }
            parent::startTransaction();

            DelegatePriceUtility::editPrice($priceIn, $id, "Service");

            parent::commitTransaction();
            $this->ok(TranslatorUtility::__translate("INFO_SERVICE_EDIT", $this->localefile));
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_SERVICE_EDIT");
            return false;
        }
    }

    // ------------------ DISCOUNT
    function addDiscount($discountIn, $id) {
        $this->LOG_FUNCTION = "addDiscount";
        try {
            if (empty($discountIn) || empty($id)) {
                DelegateUtility::paramsNull($this, "ERROR_SERVICE_EDIT");
                return false;
            }
            parent::startTransaction();

            DelegatePriceUtility::addDiscount($discountIn, $id, "Service");

            parent::commitTransaction();
            $this->ok(TranslatorUtility::__translate("INFO_SERVICE_EDIT", $this->localefile));
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_SERVICE_EDIT");
            return false;
        }
    }

    function editDiscount($discountIn, $id_discount, $id) {
        $this->LOG_FUNCTION = "editDiscount";
        try {
            if (empty($discountIn) || empty($id_discount) || empty($id)) {
                DelegateUtility::paramsNull($this, "ERROR_SERVICE_EDIT");
                return false;
            }
            parent::startTransaction();

            DelegatePriceUtility::editDiscount($discountIn, $id_discount, $id, "Service");

            parent::commitTransaction();
            $this->ok(TranslatorUtility::__translate("INFO_SERVICE_EDIT", $this->localefile));
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_SERVICE_EDIT");
            return false;
        }
    }

    function deleteDiscount($id_discount, $id) {
        $this->LOG_FUNCTION = "deleteDiscount";
        try {
            if (empty($id_discount) || empty($id)) {
                DelegateUtility::paramsNull($this, "ERROR_SERVICE_EDIT");
                return false;
            }
            parent::startTransaction();

            DelegatePriceUtility::deleteDiscount($id_discount, $id, "Service");

            parent::commitTransaction();
            $this->ok(TranslatorUtility::__translate("INFO_SERVICE_EDIT", $this->localefile));
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_SERVICE_EDIT");
            return false;
        }
    }

    // ------------------ TAX
    function addTax($taxIn, $id) {
        $this->LOG_FUNCTION = "addTax";
        try {
            if (empty($taxIn) || empty($id)) {
                DelegateUtility::paramsNull($this, "ERROR_SERVICE_EDIT");
                return false;
            }
            parent::startTransaction();

            DelegatePriceUtility::addTax($this, $taxIn, $id, "Service");

            parent::commitTransaction();
            $this->ok(TranslatorUtility::__translate("INFO_SERVICE_EDIT", $this->localefile));
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_SERVICE_EDIT");
            return false;
        }
    }

    function editTax($taxIn, $id_tax, $id) {
        $this->LOG_FUNCTION = "editTax";
        try {
            if (empty($taxIn) || empty($id_tax) || empty($id)) {
                DelegateUtility::paramsNull($this, "ERROR_SERVICE_EDIT");
                return false;
            }
            parent::startTransaction();

            DelegatePriceUtility::editTax($this, $taxIn, $id_tax, $id, "Service");

            parent::commitTransaction();
            $this->ok(TranslatorUtility::__translate("INFO_SERVICE_EDIT", $this->localefile));
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_SERVICE_EDIT");
            return false;
        }
    }

    function deleteTax($id_tax, $id) {
        $this->LOG_FUNCTION = "deleteTax";
        try {
            if (empty($id_tax) || empty($id)) {
                DelegateUtility::paramsNull($this, "ERROR_SERVICE_EDIT");
                return false;
            }
            parent::startTransaction();

            DelegatePriceUtility::deleteTax($this, $id_tax, $id, "Service");

            parent::commitTransaction();
            $this->ok(TranslatorUtility::__translate("INFO_SERVICE_EDIT", $this->localefile));
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_SERVICE_EDIT");
            return false;
        }
    }
}
