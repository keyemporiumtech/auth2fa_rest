<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
// inner
App::uses("PocketBS", "modules/shop_warehouse/business");
App::uses("FileUtility", "modules/coreutils/utility");
App::uses("PocketproductBS", "modules/shop_warehouse/business");
App::uses("ProductBS", "modules/shop_warehouse/business");
App::uses("ProductUI", "modules/shop_warehouse/delegate");
App::uses("PocketserviceBS", "modules/shop_warehouse/business");
App::uses("ServiceBS", "modules/shop_warehouse/business");
App::uses("ServiceUI", "modules/shop_warehouse/delegate");
App::uses("PriceBS", "modules/shop_warehouse/business");
App::uses("PriceUI", "modules/shop_warehouse/delegate");

class PocketUI extends AppGenericUI {

    function __construct() {
        parent::__construct("PocketUI");
        $this->localefile = "pocket";
        $this->obj = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid_medium()),
            new ObjPropertyEntity("name", null, ""),
            new ObjPropertyEntity("description", null, ""),
            new ObjPropertyEntity("image", null, 0),
            new ObjPropertyEntity("price", null, 0),
            new ObjPropertyEntity("note", null, ""),
            new ObjPropertyEntity("flgreleted", null, 0),
            new ObjPropertyEntity("flgdeleted", null, 0),
        );
    }

    function get($id = null, $cod = null) {
        $this->LOG_FUNCTION = "get";
        try {
            if (empty($id) && empty($cod)) {
                DelegateUtility::paramsNull($this, "ERROR_POCKET_NOT_FOUND");
                return "";
            }
            $pocketBS = new PocketBS();
            $pocketBS->json = $this->json;
            parent::completeByJsonFkVf($pocketBS);
            if (!empty($cod)) {
                $pocketBS->addCondition("cod", $cod);
            }
            $this->ok();
            return $pocketBS->unique($id);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_POCKET_NOT_FOUND");
            return "";
        }
    }

    function table($conditions = null, $orders = null, $paginate = null, $bs = null) {
        $this->LOG_FUNCTION = "table";
        try {
            $pocketBS = !empty($bs) ? $bs : new PocketBS();
            $pocketBS->json = $this->json;
            parent::completeByJsonFkVf($pocketBS);
            parent::evalConditions($pocketBS, $conditions);
            parent::evalOrders($pocketBS, $orders);
            $pockets = $pocketBS->table($conditions, $orders, $paginate);
            parent::evalPagination($pocketBS, $paginate);
            $this->ok();
            return parent::paginateForResponse($pockets);
        } catch (Exception $e) {
            DelegateUtility::eccezione($e, $this, "ERROR_NO_DATA_FOUND", "errors");
            return "";
        }
    }

    function save($pocketIn) {
        $this->LOG_FUNCTION = "save";
        try {
            parent::startTransaction();

            $pocket = DelegateUtility::getEntityToSave(new PocketBS(), $pocketIn, $this->obj);

            if (!empty($pocket)) {

                $pocketBS = new PocketBS();
                $id_pocket = $pocketBS->save($pocket);
                parent::saveInGroup($pocketBS, $id_pocket);

                parent::commitTransaction();
                if (!empty($id_pocket)) {
                    $this->ok(TranslatorUtility::__translate("INFO_POCKET_SAVE", $this->localefile));
                    return $id_pocket;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonSalvato($this, "ERROR_POCKET_SAVE");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaSalvare($this, "ERROR_POCKET_SAVE");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_POCKET_SAVE");
            return 0;
        }
    }

    function edit($id, $pocketIn) {
        $this->LOG_FUNCTION = "edit";
        try {
            parent::startTransaction();

            $pocket = DelegateUtility::getEntityToEdit(new PocketBS(), $pocketIn, $this->obj, $id);

            if (!empty($pocket)) {
                $pocketBS = new PocketBS();
                $id_pocket = $pocketBS->save($pocket);
                parent::saveInGroup($pocketBS, $id_pocket);
                parent::delInGroup($pocketBS, $id_pocket);

                parent::commitTransaction();
                if (!empty($id_pocket)) {
                    $this->ok(TranslatorUtility::__translate("INFO_POCKET_EDIT", $this->localefile));
                    return $id_pocket;
                } else {
                    parent::rollbackTransaction();
                    DelegateUtility::nonModificato($this, "ERROR_POCKET_EDIT");
                    return 0;
                }
            } else {
                parent::rollbackTransaction();
                DelegateUtility::oggettoVuotoDaModificare($this, "ERROR_POCKET_EDIT");
                return 0;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_POCKET_EDIT");
            return 0;
        }
    }

    function delete($id) {
        $this->LOG_FUNCTION = "delete";
        try {
            parent::startTransaction();
            if (!empty($id)) {
                $pocketBS = new PocketBS();
                $pocketBS->delete($id);
                parent::delInGroup($pocketBS, $id, true);

                parent::commitTransaction();
                $this->ok(TranslatorUtility::__translate("INFO_POCKET_DELETE", $this->localefile));
                return true;
            } else {
                parent::rollbackTransaction();
                DelegateUtility::idNulloDaEliminare($this, "ERROR_POCKET_DELETE");
                return false;
            }
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_POCKET_DELETE");
            return false;
        }
    }

    // ------------------ RELATIONS
    function addProduct($productIn = null, $id_product = null, $priceIn = null, $id = null) {
        $this->LOG_FUNCTION = "addProduct";
        try {
            if ((empty($productIn) && empty($id_product)) || empty($id)) {
                DelegateUtility::paramsNull($this, "ERROR_POCKET_EDIT");
                return false;
            }
            parent::startTransaction();
            $productUI = new ProductUI();
            $productBS = new ProductBS();
            if (!empty($productIn)) {
                $product = DelegateUtility::getEntityToSave($productBS, $productIn, $productUI->obj);
                $id_product = $productBS->save($product);
            } else {
                $product = $productBS->unique($id_product);
            }

            if (!empty($priceIn)) {
                $priceUI = new PriceUI();
                $priceBS = new PriceBS();
                $price = DelegateUtility::getEntityToSave($priceBS, $priceIn, $priceUI->obj);
                if (!empty($product['Product']['price'])) {
                    $price['Price']['id'] = $product['Product']['price'];
                }
                $id_price = $priceBS->save($price);
                $productBS = new ProductBS();
                $productBS->updateField($id_product, 'price', $id_price);
            }

            $pocketproductBS = new PocketproductBS();
            $pocketproduct = $pocketproductBS->instance();
            $pocketproduct['Pocketproduct']['pocket'] = $id;
            $pocketproduct['Pocketproduct']['product'] = $id_product;
            $id_pocketproduct = $pocketproductBS->save($pocketproduct);

            // aggiorno gli importi se pocket è related
            $pocketBS = new PocketBS();
            $pocket = $pocketBS->unique($id);
            if ($pocket['Pocket']['flgrelated'] == 1) {
                $price = PriceUtility::setPricePocket($id);
                $priceBS = new PriceBS();
                $priceBS->save($price);
            }

            parent::commitTransaction();
            $this->ok(TranslatorUtility::__translate("INFO_POCKET_EDIT", $this->localefile));
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_POCKET_EDIT");
            return false;
        }
    }

    function addService($serviceIn = null, $id_service = null, $priceIn = null, $id = null) {
        $this->LOG_FUNCTION = "addService";
        try {
            if ((empty($serviceIn) && empty($id_service)) || empty($id)) {
                DelegateUtility::paramsNull($this, "ERROR_POCKET_EDIT");
                return false;
            }
            parent::startTransaction();
            $serviceUI = new ServiceUI();
            $serviceBS = new ServiceBS();
            if (!empty($serviceIn)) {
                $service = DelegateUtility::getEntityToSave($serviceBS, $serviceIn, $serviceUI->obj);
                $id_service = $serviceBS->save($service);
            } else {
                $service = $serviceBS->unique($id_service);
            }

            if (!empty($priceIn)) {
                $priceUI = new PriceUI();
                $priceBS = new PriceBS();
                $price = DelegateUtility::getEntityToSave($priceBS, $priceIn, $priceUI->obj);
                if (!empty($service['Service']['price'])) {
                    $price['Price']['id'] = $service['Service']['price'];
                }
                $id_price = $priceBS->save($price);
                $serviceBS = new ServiceBS();
                $serviceBS->updateField($id_service, 'price', $id_price);
            }

            $pocketserviceBS = new PocketserviceBS();
            $pocketservice = $pocketserviceBS->instance();
            $pocketservice['Pocketservice']['pocket'] = $id;
            $pocketservice['Pocketservice']['service'] = $id_service;
            $id_pocketservice = $pocketserviceBS->save($pocketservice);

            // aggiorno gli importi se pocket è related
            $pocketBS = new PocketBS();
            $pocket = $pocketBS->unique($id);
            if ($pocket['Pocket']['flgrelated'] == 1) {
                $price = PriceUtility::setPricePocket($id);
                $priceBS = new PriceBS();
                $priceBS->save($price);
            }

            parent::commitTransaction();
            $this->ok(TranslatorUtility::__translate("INFO_POCKET_EDIT", $this->localefile));
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_POCKET_EDIT");
            return false;
        }
    }

    // ------------------ PRICE
    function addPrice($priceIn, $id) {
        $this->LOG_FUNCTION = "addPrice";
        try {
            if (empty($priceIn) || empty($id)) {
                DelegateUtility::paramsNull($this, "ERROR_POCKET_EDIT");
                return false;
            }
            parent::startTransaction();

            DelegatePriceUtility::addPrice($priceIn, $id, "Pocket");

            parent::commitTransaction();
            $this->ok(TranslatorUtility::__translate("INFO_POCKET_EDIT", $this->localefile));
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_POCKET_EDIT");
            return false;
        }
    }

    function editPrice($priceIn, $id) {
        $this->LOG_FUNCTION = "editPrice";
        try {
            if (empty($priceIn) || empty($id)) {
                DelegateUtility::paramsNull($this, "ERROR_POCKET_EDIT");
                return false;
            }
            parent::startTransaction();

            DelegatePriceUtility::editPrice($priceIn, $id, "Pocket");

            parent::commitTransaction();
            $this->ok(TranslatorUtility::__translate("INFO_POCKET_EDIT", $this->localefile));
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_POCKET_EDIT");
            return false;
        }
    }

    // ------------------ DISCOUNT
    function addDiscount($discountIn, $id) {
        $this->LOG_FUNCTION = "addDiscount";
        try {
            if (empty($discountIn) || empty($id)) {
                DelegateUtility::paramsNull($this, "ERROR_POCKET_EDIT");
                return false;
            }
            parent::startTransaction();

            DelegatePriceUtility::addDiscount($discountIn, $id, "Pocket");

            parent::commitTransaction();
            $this->ok(TranslatorUtility::__translate("INFO_POCKET_EDIT", $this->localefile));
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_POCKET_EDIT");
            return false;
        }
    }

    function editDiscount($discountIn, $id_discount, $id) {
        $this->LOG_FUNCTION = "editDiscount";
        try {
            if (empty($discountIn) || empty($id_discount) || empty($id)) {
                DelegateUtility::paramsNull($this, "ERROR_POCKET_EDIT");
                return false;
            }
            parent::startTransaction();

            DelegatePriceUtility::editDiscount($discountIn, $id_discount, $id, "Pocket");

            parent::commitTransaction();
            $this->ok(TranslatorUtility::__translate("INFO_POCKET_EDIT", $this->localefile));
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_POCKET_EDIT");
            return false;
        }
    }

    function deleteDiscount($id_discount, $id) {
        $this->LOG_FUNCTION = "deleteDiscount";
        try {
            if (empty($id_discount) || empty($id)) {
                DelegateUtility::paramsNull($this, "ERROR_POCKET_EDIT");
                return false;
            }
            parent::startTransaction();

            DelegatePriceUtility::deleteDiscount($id_discount, $id, "Pocket");

            parent::commitTransaction();
            $this->ok(TranslatorUtility::__translate("INFO_POCKET_EDIT", $this->localefile));
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_POCKET_EDIT");
            return false;
        }
    }

    // ------------------ TAX
    function addTax($taxIn, $id) {
        $this->LOG_FUNCTION = "addTax";
        try {
            if (empty($taxIn) || empty($id)) {
                DelegateUtility::paramsNull($this, "ERROR_POCKET_EDIT");
                return false;
            }
            parent::startTransaction();

            DelegatePriceUtility::addTax($this, $taxIn, $id, "Pocket");

            parent::commitTransaction();
            $this->ok(TranslatorUtility::__translate("INFO_POCKET_EDIT", $this->localefile));
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_POCKET_EDIT");
            return false;
        }
    }

    function editTax($taxIn, $id_tax, $id) {
        $this->LOG_FUNCTION = "editTax";
        try {
            if (empty($taxIn) || empty($id_tax) || empty($id)) {
                DelegateUtility::paramsNull($this, "ERROR_POCKET_EDIT");
                return false;
            }
            parent::startTransaction();

            DelegatePriceUtility::editTax($this, $taxIn, $id_tax, $id, "Pocket");

            parent::commitTransaction();
            $this->ok(TranslatorUtility::__translate("INFO_POCKET_EDIT", $this->localefile));
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_POCKET_EDIT");
            return false;
        }
    }

    function deleteTax($id_tax, $id) {
        $this->LOG_FUNCTION = "deleteTax";
        try {
            if (empty($id_tax) || empty($id)) {
                DelegateUtility::paramsNull($this, "ERROR_POCKET_EDIT");
                return false;
            }
            parent::startTransaction();

            DelegatePriceUtility::deleteTax($this, $id_tax, $id, "Pocket");

            parent::commitTransaction();
            $this->ok(TranslatorUtility::__translate("INFO_POCKET_EDIT", $this->localefile));
            return true;
        } catch (Exception $e) {
            parent::rollbackTransaction();
            DelegateUtility::eccezione($e, $this, "ERROR_POCKET_EDIT");
            return false;
        }
    }
}
