<?php
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("PriceUtility", "modules/shop_warehouse/utility");
App::uses("DiscountBS", "modules/shop_warehouse/business");
App::uses("DiscountUI", "modules/shop_warehouse/delegate");
App::uses("PriceBS", "modules/shop_warehouse/business");
App::uses("PriceUI", "modules/shop_warehouse/delegate");

class DelegatePriceUtility {

    // ------------------ PRICE
    public static function addPrice($priceIn, $id, $entityName) {
        try {
            $bsClass = $entityName . "BS";
            $lowerName = strtolower($entityName);
            $fkDiscountName = $entityName . "discount";
            $bsFkDiscountClass = $fkDiscountName . "BS";
            $fkTaxName = $entityName . "tax";
            $bsFkTaxClass = $fkTaxName . "BS";

            $entityBS = new $bsClass();
            $entity = $entityBS->unique($id);

            // aggiungo il price
            $priceUI = new PriceUI();
            $priceBS = new PriceBS();
            $priceInObj = DelegateUtility::getEntityToSave($priceBS, $priceIn, $priceUI->obj);
            $id_price = $priceBS->save($priceInObj);
            // aggiorno il price dell'entity
            $entityBS->updateField($id, "price", $id_price);

            // aggiorno l'iva
            $price = PriceUtility::setPriceIva($id_price);
            $priceBS = new PriceBS();
            $priceBS->save($price);

            // aggiorno gli sconti
            $fkdiscountBS = new $bsFkDiscountClass();
            $fkdiscountBS->addCondition($lowerName, $id);
            $fkdiscounts = $fkdiscountBS->all();
            $discounts = array();
            foreach ($fkdiscounts as $fkdiscount) {
                $discountBS = new DiscountBS();
                array_push($discounts, $discountBS->unique($fkdiscount[$fkDiscountName]['discount']));
            }

            $price = PriceUtility::setPriceDiscount($id_price, $discounts);
            $priceBS = new PriceBS();
            $priceBS->save($price);

            // aggiorno le tasse
            $fktaxBS = new $bsFkTaxClass();
            $fktaxBS->addCondition($lowerName, $id);
            $taxes = $fktaxBS->all();

            $price = PriceUtility::setPriceTax($id_price, $fkTaxName, $taxes);
            $priceBS = new PriceBS();
            $priceBS->save($price);
        } catch (Exception $e) {
            throw ($e);
        }
    }

    public static function editPrice($priceIn, $id, $entityName) {
        try {
            $bsClass = $entityName . "BS";
            $lowerName = strtolower($entityName);
            $fkDiscountName = $entityName . "discount";
            $bsFkDiscountClass = $fkDiscountName . "BS";
            $fkTaxName = $entityName . "tax";
            $bsFkTaxClass = $fkTaxName . "BS";

            $entityBS = new $bsClass();
            $entity = $entityBS->unique($id);
            $id_price = $entity[$entityName]['price'];

            // aggiorno il price
            $priceUI = new PriceUI();
            $priceBS = new PriceBS();
            $priceInObj = DelegateUtility::getEntityToEdit($priceBS, $priceIn, $priceUI->obj, $id_price);
            $priceBS->save($priceInObj);

            // aggiorno l'iva
            $price = PriceUtility::setPriceIva($id_price);
            $priceBS = new PriceBS();
            $priceBS->save($price);

            // aggiorno gli sconti
            $fkdiscountBS = new $bsFkDiscountClass();
            $fkdiscountBS->addCondition($lowerName, $id);
            $fkdiscounts = $fkdiscountBS->all();
            $discounts = array();
            foreach ($fkdiscounts as $fkdiscount) {
                $discountBS = new DiscountBS();
                array_push($discounts, $discountBS->unique($fkdiscount[$fkDiscountName]['discount']));
            }

            $price = PriceUtility::setPriceDiscount($id_price, $discounts);
            $priceBS = new PriceBS();
            $priceBS->save($price);

            // aggiorno le tasse
            $fktaxBS = new $bsFkTaxClass();
            $fktaxBS->addCondition($lowerName, $id);
            $taxes = $fktaxBS->all();

            $price = PriceUtility::setPriceTax($id_price, $fkTaxName, $taxes);
            $priceBS = new PriceBS();
            $priceBS->save($price);
        } catch (Exception $e) {
            throw ($e);
        }
    }

    // ------------------ DISCOUNT
    public static function addDiscount($discountIn, $id, $entityName) {
        try {

            $bsClass = $entityName . "BS";
            $fkName = $entityName . "discount";
            $bsFkClass = $fkName . "BS";
            $lowerName = strtolower($entityName);

            $entityBS = new $bsClass();
            $entity = $entityBS->unique($id);

            // valuto se lo sconto è applicabile al carrello
            $discountUI = new DiscountUI();
            $discountBS = new DiscountBS();
            $discountInObj = DelegateUtility::getEntityToSave($discountBS, $discountIn, $discountUI->obj);
            if (!empty($discountInObj['Discount']['levelquantity']) || !empty($discountInObj['Discount']['levelprice'])) {
                $discountInObj['Discount']['flgsystem'] = 1;
            }

            // elimino tutti gli sconti scaduti
            $discountBS = new DiscountBS();
            $discountBS->cleanExpiredForeign("{$lowerName}discounts", "{$lowerName}", $id);

            // salvo il nuovo sconto
            $discountBS = new DiscountBS();
            $id_discount = $discountBS->save($discountInObj);
            if (empty($id_discount)) {
                throw new Exception("Discount not saved");
            }

            $fkdiscountBS = new $bsFkClass();
            $fkdiscount = $fkdiscountBS->instance();
            $fkdiscount[$fkName][$lowerName] = $id;
            $fkdiscount[$fkName]['discount'] = $id_discount;
            $id_fkdiscount = $fkdiscountBS->save($fkdiscount);
            if (empty($id_fkdiscount)) {
                throw new Exception("Discount not saved");
            }

            // aggiorno gli importi
            $fkdiscountBS = new $bsFkClass();
            $fkdiscountBS->addCondition($lowerName, $id);
            $fkdiscounts = $fkdiscountBS->all();
            $discounts = array();
            foreach ($fkdiscounts as $fkdiscount) {
                $discountBS = new DiscountBS();
                array_push($discounts, $discountBS->unique($fkdiscount[$fkName]['discount']));
            }

            $price = PriceUtility::setPriceDiscount($entity[$entityName]['price'], $discounts);
            $priceBS = new PriceBS();
            $priceBS->save($price);

            return true;
        } catch (Exception $e) {
            throw ($e);
        }
    }

    public static function editDiscount($discountIn, $id_discount, $id, $entityName) {
        try {
            $bsClass = $entityName . "BS";
            $fkName = $entityName . "discount";
            $bsFkClass = $fkName . "BS";
            $lowerName = strtolower($entityName);

            $entityBS = new $bsClass();
            $entity = $entityBS->unique($id);

            // valuto se lo sconto è applicabile al carrello
            $discountUI = new DiscountUI();
            $discountBS = new DiscountBS();
            $discountInObj = DelegateUtility::getEntityToEdit($discountBS, $discountIn, $discountUI->obj, $id_discount);
            if (!empty($discountInObj['Discount']['levelquantity']) || !empty($discountInObj['Discount']['levelprice'])) {
                $discountInObj['Discount']['flgsystem'] = 1;
            }

            // elimino tutti gli sconti scaduti
            $discountBS = new DiscountBS();
            $discountBS->cleanExpiredForeign("productdiscounts", "product", $id);

            // aggiorno lo sconto
            $discountBS = new DiscountBS();
            $id_discount = $discountBS->save($discountInObj);
            if (empty($id_discount)) {
                throw new Exception("Discount not saved");
            }

            // aggiorno gli importi
            $fkdiscountBS = new $bsFkClass();
            $fkdiscountBS->addCondition($lowerName, $id);
            $fkdiscounts = $fkdiscountBS->all();
            $discounts = array();
            foreach ($fkdiscounts as $fkdiscount) {
                $discountBS = new DiscountBS();
                array_push($discounts, $discountBS->unique($fkdiscount[$fkName]['discount']));
            }

            $price = PriceUtility::setPriceDiscount($entity[$entityName]['price'], $discounts);
            $priceBS = new PriceBS();
            $priceBS->save($price);

            return true;
        } catch (Exception $e) {
            throw ($e);
        }
    }

    public static function deleteDiscount($id_discount, $id, $entityName) {
        try {

            $bsClass = $entityName . "BS";
            $fkName = $entityName . "discount";
            $bsFkClass = $fkName . "BS";
            $lowerName = strtolower($entityName);

            $entityBS = new $bsClass();
            $entity = $entityBS->unique($id);

            // elimino lo sconto
            $discountBS = new DiscountBS();
            $discountBS->deleteForeign("{$lowerName}discounts", "{$lowerName}", $id, $id_discount);

            // elimino tutti gli sconti scaduti
            $discountBS = new DiscountBS();
            $discountBS->cleanExpiredForeign("{$lowerName}discounts", "{$lowerName}", $id);

            // aggiorno gli importi
            $fkdiscountBS = new $bsFkClass();
            $fkdiscountBS->addCondition($lowerName, $id);
            $fkdiscounts = $fkdiscountBS->all();
            $discounts = array();
            foreach ($fkdiscounts as $fkdiscount) {
                $discountBS = new DiscountBS();
                array_push($discounts, $discountBS->unique($fkdiscount[$fkName]['discount']));
            }

            $price = PriceUtility::setPriceDiscount($entity[$entityName]['price'], $discounts);
            $priceBS = new PriceBS();
            $priceBS->save($price);

            return true;
        } catch (Exception $e) {
            throw ($e);
        }
    }

    // ------------------ TAX
    public static function addTax($ui, $taxIn, $id, $entityName) {
        try {
            $bsClass = $entityName . "BS";
            $fkName = $entityName . "tax";
            $bsFkClass = $fkName . "BS";
            $lowerName = strtolower($entityName);
            $uiFkClass = $fkName . "UI";

            $entityBS = new $bsClass();
            $entity = $entityBS->unique($id);

            // assegno l'id dell'entity relazionata
            $fktaxUI = new $uiFkClass();
            $fktaxBS = new $bsFkClass();
            $fktaxObj = DelegateUtility::getEntityToSave($fktaxBS, $taxIn, $fktaxUI->obj);
            $fktaxObj[$fkName][$entityName] = $id;

            // salvo il nuovo tax
            $fktaxBS = new $bsFkClass();
            $id_fktax = $fktaxBS->save($fktaxObj);
            if (empty($id_fktax)) {
                throw new Exception("Tax not saved");
            }

            // aggiorno gli importi
            $fktaxBS = new $bsFkClass();
            $fktaxBS->addCondition($lowerName, $id);
            $taxes = $fktaxBS->all();

            $price = PriceUtility::setPriceTax($entity[$entityName]['price'], $fkName, $taxes);
            $priceBS = new PriceBS();
            $priceBS->save($price);
        } catch (Exception $e) {
            throw ($e);
        }
    }

    public static function editTax($ui, $taxIn, $id_tax, $id, $entityName) {
        try {
            $bsClass = $entityName . "BS";
            $fkName = $entityName . "tax";
            $bsFkClass = $fkName . "BS";
            $lowerName = strtolower($entityName);
            $uiFkClass = $fkName . "UI";

            $entityBS = new $bsClass();
            $entity = $entityBS->unique($id);

            // assegno l'id dell'entity relazionata
            $fktaxUI = new $uiFkClass();
            $fktaxBS = new $bsFkClass();
            $fktaxObj = DelegateUtility::getEntityToEdit($fktaxBS, $taxIn, $fktaxUI->obj, $id_tax);
            $fktaxObj[$fkName][$entityName] = $id;

            // salvo il nuovo tax
            $fktaxBS = new $bsFkClass();
            $id_fktax = $fktaxBS->save($fktaxObj);
            if (empty($id_fktax)) {
                throw new Exception("Tax not updated");
            }

            // aggiorno gli importi
            $fktaxBS = new $bsFkClass();
            $fktaxBS->addCondition($lowerName, $id);
            $taxes = $fktaxBS->all();

            $price = PriceUtility::setPriceTax($entity[$entityName]['price'], $fkName, $taxes);
            $priceBS = new PriceBS();
            $priceBS->save($price);
        } catch (Exception $e) {
            throw ($e);
        }
    }

    public static function deleteTax($ui, $id_tax, $id, $entityName) {
        try {
            $bsClass = $entityName . "BS";
            $fkName = $entityName . "tax";
            $bsFkClass = $fkName . "BS";
            $lowerName = strtolower($entityName);
            $uiFkClass = $fkName . "UI";

            $entityBS = new $bsClass();
            $entity = $entityBS->unique($id);

            // cancello il tax
            $fktaxBS = new $bsFkClass();
            $flg_fktax = $fktaxBS->delete($id_tax);
            if (!$flg_fktax) {
                throw new Exception("Tax not deleted");
            }

            // aggiorno gli importi
            $fktaxBS = new $bsFkClass();
            $fktaxBS->addCondition($lowerName, $id);
            $taxes = $fktaxBS->all();

            $price = PriceUtility::setPriceTax($entity[$entityName]['price'], $fkName, $taxes);
            $priceBS = new PriceBS();
            $priceBS->save($price);
        } catch (Exception $e) {
            throw ($e);
        }
    }
}