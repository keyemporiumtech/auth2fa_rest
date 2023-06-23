<?php
App::uses("AddressBS", "modules/localesystem/business");
App::uses("NationBS", "modules/localesystem/business");

class AddressUtility {

    static function getAddressString($id = null, $cod = null, $separator = null, $flgRegion = true) {
        if (empty($id) && empty($cod)) {
            return null;
        }
        $addressBS = new AddressBS();
        if (!empty($cod)) {
            $addressBS->addCondition("cod", $cod);
        }
        $address = $addressBS->unique($id);
        if (empty($address)) {
            return null;
        }

        if (empty($separator)) {
            $separator = " - ";
        }

        $string = "";
        if (!empty($address['Address']['street'])) {
            $string .= $address['Address']['street'];
            if (!empty($address['Address']['number'])) {
                $string .= ", " . $address['Address']['number'];
            }
        } else {
            $string = "ND";
        }
        if (!empty($address['Address']['zip'])) {
            $string .= $separator . $address['Address']['zip'];
        }
        if (!empty($address['Address']['city'])) {
            $string .= " - " . $address['Address']['city'];
        }
        if (!empty($address['Address']['province'])) {
            $string .= " (" . $address['Address']['province'] . ")";
        }
        if (!empty($address['Address']['region']) && $flgRegion) {
            $string .= " (" . $address['Address']['region'] . ")";
        }
        if (!empty($address['Address']['nation'])) {
            $nationBS = new NationBS();
            $nation = $nationBS->unique($address['Address']['nation']);
            $string .= $separator . $nation['Nation']['name'];
        }
        return $string;
    }

    static function getAddressStringByValues($street = null, $number = null, $zip = null, $city = null, $province = null, $region = null, $nation = null, $separator = null, $flgRegion = false) {
        if (empty($separator)) {
            $separator = " - ";
        }

        $string = "";
        if (!empty($street)) {
            $string .= $street;
            if (!empty($number)) {
                $string .= ", " . $number;
            }
        } else {
            $string = "ND";
        }
        if (!empty($zip)) {
            $string .= $separator . $zip;
        }
        if (!empty($city)) {
            $string .= " - " . $city;
        }
        if (!empty($province)) {
            $string .= " (" . $province . ")";
        }
        if (!empty($region) && $flgRegion) {
            $string .= " (" . $region . ")";
        }
        if (!empty($nation)) {
            $string .= $separator . $nation;
        }
        return $string;
    }
}