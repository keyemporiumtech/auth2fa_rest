<?php
App::uses("EnumIVAType", "modules/shop_warehouse/config");
App::uses("MathUtility", "modules/coreutils/utility");
App::uses("PriceBS", "modules/shop_warehouse/business");
App::uses("PocketBS", "modules/shop_warehouse/business");
App::uses("PocketproductBS", "modules/shop_warehouse/business");
App::uses("PocketserviceBS", "modules/shop_warehouse/business");

class PriceUtility {

    public static function setPriceIva($id, $rate = null, $iva = null, $iva_percent = null, $flg_ivainclude = EnumIVAType::INCLUDED) {
        $priceBS = new PriceBS();
        $price = $priceBS->unique($id);

        if (MathUtility::isEmptyDecimal($rate)) {
            $rate = $price['Price']['total'];
        }
        if (MathUtility::isEmptyDecimal($iva)) {
            $iva = $price['Price']['iva'];
        }
        if (MathUtility::isEmptyDecimal($iva_percent)) {
            $iva_percent = $price['Price']['iva_percent'];
        }
        if (empty($flg_ivainclude)) {
            $flg_ivainclude = EnumIVAType::INCLUDED;
        }

        $arrPrices = null;
        if (MathUtility::isEmptyDecimal($iva) && !MathUtility::isEmptyDecimal($iva_percent)) {
            $arrPrices = PriceUtility::calcIva($rate, $iva_percent, $flg_ivainclude);
        } elseif (MathUtility::isEmptyDecimal($iva_percent) && !MathUtility::isEmptyDecimal($iva)) {
            $arrPrices = PriceUtility::calcIva($rate, $iva_percent, $flg_ivainclude);
        }

        if (!empty($arrPrices)) {
            $price['Price']['total'] = $arrPrices['total'];
            $price['Price']['price'] = $arrPrices['price'];
            $price['Price']['iva'] = $arrPrices['iva'];
            $price['Price']['iva_percent'] = $arrPrices['iva_percent'];
        }
        return $price;
    }

    public static function setPriceDiscount($id, $discounts = array()) {
        $priceBS = new PriceBS();
        $price = $priceBS->unique($id);
        $discount = 0.00;
        $discount_percent = 0.00;
        foreach ($discounts as $discount) {
            $conditionLevel = true;
            if (!empty($discount['Discount']['levelquantity']) || !empty($discount['Discount']['levelprice']) || $discount['Discount']['flgsystem'] == 1) {
                continue;
            }

            if (!empty($discount['Discount']['dtaend']) && !DateUtility::endMax($discount['Discount']['dtaend'], date('Y-m-d H:i:s'))) {
                continue;
            }
            $RN_discount = round($discount['Discount']['discount']);
            $RN_discount_percent = round($discount['Discount']['discount_percent']);
            if (!empty($RN_discount) && $RN_discount > $discount) {
                $discount = $RN_discount;
            }
            if (!empty($RN_discount_percent) && $RN_discount_percent > $discount_percent) {
                $discount_percent = $RN_discount_percent;
            }
        }
        if (!empty($discount) && empty($discount_percent)) {
            $discount_percent = PriceUtility::calcDiscountPercent($price['Price']['total'], $discount);
        }
        if (empty($discount) && !empty($discount_percent)) {
            $discount = PriceUtility::calcDiscount($price['Price']['total'], $discount_percent);
        }

        $price['Price']['discount'] = $discount;
        $price['Price']['discount_percent'] = $discount_percent;
        return $price;
    }

    public static function setPriceTax($id, $entityTaxName, $taxes = array()) {
        $priceBS = new PriceBS();
        $price = $priceBS->unique($id);
        $tax = 0.00;
        $RN_total = round($price['Price']['total'], 2);
        if (!empty($RN_total)) {
            foreach ($taxes as $entitytax) {
                $RN_tax = $entitytax[$entityTaxName]['tax'];
                $RN_tax_percent = $entitytax[$entityTaxName]['tax_percent'];
                if (!empty($RN_tax)) {
                    $tax += $RN_tax;
                } elseif (empty($RN_tax) && !empty($RN_tax_percent)) {
                    $DBLtax = PriceUtility::calcTax($RN_total, $RN_tax_percent);
                    $tax += $DBLtax;
                }
            }
        }
        $price['Price']['tax'] = round($tax, 2);
        return $price;
    }

    public static function calcIva($total, $iva_percent, $flg_ivainclude = EnumIVAType::INCLUDED) {
        $RN_total = round($total, 2);
        $RN_iva_percent = round($iva_percent, 2);
        $price = 0.00;
        $iva = 0.00;
        if (!empty($RN_total) && empty($RN_iva_percent)) {
            $price = $RN_total;
        } elseif (!empty($RN_total) && !empty($RN_iva_percent)) {
            switch ($flg_ivainclude) {
            case EnumIVAType::FREE:
                $price = $RN_total;
                $iva_percent = 0.00;
                break;
            case EnumIVAType::EXCLUDED:
                $DBLpercentIva = MathUtility::getUnitPercentValue($iva_percent);
                $DBLtotal = round(($RN_total * $DBLpercentIva), 2);
                $price = $RN_total;
                $iva = round(($DBLtotal - $price), 2);
                break;
            case EnumIVAType::INCLUDED:
                $DBLpercentIva = MathUtility::getUnitPercentValue($iva_percent);
                $price = round(($RN_total / $DBLpercentIva), 2);
                $iva = round(($RN_total - $price), 2);
                break;
            }
        }
        return array(
            "total" => round(($price + $iva), 2),
            "price" => $price,
            "iva" => $iva,
            "iva_percent" => $iva_percent,
        );
    }

    public static function calcIvaPercent($total, $iva, $flg_ivainclude = EnumIVAType::INCLUDED) {
        $RN_total = round($total, 2);
        $RN_iva = round($iva, 2);
        $price = 0.00;
        $iva_percent = 0.00;
        if (!empty($RN_total) && empty($RN_iva)) {
            $price = $RN_total;
        } elseif (!empty($RN_total) && !empty($RN_iva)) {
            switch ($flg_ivainclude) {
            case EnumIVAType::FREE:
                $price = $RN_total;
                $iva = 0.00;
                break;
            case EnumIVAType::EXCLUDED:
                $price = $RN_total;
                $DBLtotal = round(($RN_total + $RN_iva), 2);
                $iva_percent = MathUtility::getPercentByUnitValue($DBLtotal / $price);
                break;
            case EnumIVAType::INCLUDED:
                $price = round(($RN_total - $RN_iva), 2);
                $iva_percent = MathUtility::getPercentByUnitValue($RN_total / $price);
                break;
            }
        }
        return array(
            "total" => round(($price + $iva), 2),
            "price" => $price,
            "iva" => $iva,
            "iva_percent" => $iva_percent,
        );
    }

    public static function calcDiscount($total, $discount_percent) {
        $RN_total = round($total, 2);
        $discount = 0.00;
        if (!empty($RN_total) && empty($discount_percent)) {
            $discount = 0.00;
        } elseif (!empty($RN_total) && !empty($discount_percent)) {
            $discount = MathUtility::getPercentValue($discount_percent, $RN_total);
        }
        return $discount;
    }

    public static function calcDiscountPercent($total, $discount) {
        $RN_total = round($total, 2);
        $discount_percent = 0.00;
        if (!empty($RN_total) && empty($discount)) {
            $discount_percent = 0.00;
        } elseif (!empty($RN_total) && !empty($discount)) {
            $discount_percent = MathUtility::getPercent($discount, $RN_total);
        }
        return $discount_percent;
    }

    public static function calcTax($total, $tax_percent) {
        $RN_total = round($total, 2);
        $tax = 0.00;
        if (!empty($RN_total) && empty($tax_percent)) {
            $tax = 0.00;
        } elseif (!empty($RN_total) && !empty($tax_percent)) {
            $tax = MathUtility::getPercentValue($tax_percent, $RN_total);
        }
        return $tax;
    }

    public static function calcTaxPercent($total, $tax) {
        $RN_total = round($total, 2);
        $tax_percent = 0.00;
        if (!empty($RN_total) && empty($tax)) {
            $tax_percent = 0.00;
        } elseif (!empty($RN_total) && !empty($tax)) {
            $tax_percent = MathUtility::getPercent($tax, $RN_total);
        }
        return $tax_percent;
    }

    public static function resetPriceValues(&$price) {
        $price['Price']['price'] = 0.00;
        $price['Price']['total'] = 0.00;
        $price['Price']['iva'] = 0.00;
        $price['Price']['iva_percent'] = 0.00;
        $price['Price']['discount'] = 0.00;
        $price['Price']['discount_percent'] = 0.00;
        $price['Price']['tax'] = 0.00;
    }

    // ------------------------------ POCKET
    public static function setPricePocket($id_pocket) {
        $pocketBS = new PocketBS();
        $pocket = $pocketBS->unique($id_pocket);

        $priceBS = new PriceBS();
        $price = $priceBS->unique($pocket['Pocket']['price']);
        PriceUtility::resetPriceValues($price);

        $pocketproductBS = new PocketproductBS();
        $pocketproductBS->addCondition("pocket", $id_pocket);
        $pocketproductBS->addBelongsTo("product_fk");
        $pocketproducts = $pocketproductBS->all();

        foreach ($pocketproducts as $pocketproduct) {
            $product = $pocketproduct['Pocketproduct']['product_fk'];
            $priceproductBS = new PriceBS();
            $priceproduct = $priceproductBS->unique($product['price']);
            $price['Price']['price'] += $priceproduct['Price']['price'];
            $price['Price']['total'] += $priceproduct['Price']['total'];
            $price['Price']['iva'] += $priceproduct['Price']['iva'];
            $price['Price']['discount'] += $priceproduct['Price']['discount'];
            $price['Price']['tax'] += $priceproduct['Price']['tax'];
        }

        $pocketserviceBS = new PocketserviceBS();
        $pocketserviceBS->addCondition("pocket", $id_pocket);
        $pocketproductBS->addBelongsTo("service_fk");
        $pocketservices = $pocketserviceBS->all();

        foreach ($pocketservices as $pocketservice) {
            $service = $pocketservice['Pocketservice']['service_fk'];
            $priceserviceBS = new PriceBS();
            $priceservice = $priceserviceBS->unique($service['price']);
            $price['Price']['price'] += $priceservice['Price']['price'];
            $price['Price']['total'] += $priceservice['Price']['total'];
            $price['Price']['iva'] += $priceservice['Price']['iva'];
            $price['Price']['discount'] += $priceservice['Price']['discount'];
            $price['Price']['tax'] += $priceservice['Price']['tax'];
        }

        $price['Price']['iva_percent'] = PriceUtility::calcIvaPercent($price['Price']['total'], $price['Price']['iva']);
        $price['Price']['discount_percent'] = PriceUtility::calcDiscountPercent($price['Price']['total'], $price['Price']['discount']);

        return $price;
    }

    static function mergePrices(&$priceTo, $priceFrom) {
        if (!empty($priceFrom)) {
            $priceTo['Price']['price'] += $priceFrom['Price']['price'];
            $priceTo['Price']['total'] += $priceFrom['Price']['total'];
            $priceTo['Price']['iva'] += $priceFrom['Price']['iva'];
            $priceTo['Price']['discount'] += $priceFrom['Price']['discount'];
            $priceTo['Price']['tax'] += $priceFrom['Price']['tax'];
            $priceTo['Price']['iva_percent'] = PriceUtility::calcIvaPercent($priceTo['Price']['total'], $priceTo['Price']['iva']);
            $priceTo['Price']['discount_percent'] = PriceUtility::calcDiscountPercent($priceTo['Price']['total'], $priceTo['Price']['discount']);
        }
    }
}