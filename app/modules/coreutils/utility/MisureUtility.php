<?php
App::uses("EnumMisureArray", "modules/coreutils/config");

/**
 * Utility per la gestione di generiche misurazioni e distanze
 *
 * @author Giuseppe Sassone
 */
class MisureUtility {

    /**
     * Ritorna la distanza tra due geolocalizzazioni
     * @param float $lat1 latitudine punto 1
     * @param float $lon1 longitudine punto 1
     * @param float $lat2 latitudine punto 2
     * @param float $lon2 longitudine punto 2
     * @param string $unit unità del valore M=Miglia,K=Kilometri,N=Miglia Nautiche
     * @return float distanza tra due geolocalizzazioni
     */
    public static function distance($lat1, $lon1, $lat2, $lon2, $unit) {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return ($miles * 1.609344);
        } elseif ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }

    /**
     * Converte una misura in pixel
     * @param type $misure unità di misura del valore da convertire (@see EnumMisureArray)
     * @param float $value valore da convertire (di default "1")
     * @return float misura in pixel
     */
    public static function convertToPixel($misure, $value = 1) {
        switch ($misure) {
        case EnumMisureArray::$misure['meter']:
            $tax_convert = 3779.5275593333;
            return $value * $tax_convert;
            break;
        case EnumMisureArray::$misure['decimeter']:
            $tax_convert = 377.95275593333;
            return $value * $tax_convert;
            break;
        case EnumMisureArray::$misure['centimeter']:
            $tax_convert = 37.795275593333;
            return $value * $tax_convert;
            break;
        case EnumMisureArray::$misure['millimeter']:
            $tax_convert = 3.7795275593333;
            return $value * $tax_convert;
            break;
        case EnumMisureArray::$misure['point']:
            $tax_convert = 1.333333;
            return $value * $tax_convert;
            break;
        case EnumMisureArray::$misure['inches']:
            $tax_convert = 96;
            return $value * $tax_convert;
            break;
        case EnumMisureArray::$misure['feet']:
            $tax_convert = 1152;
            return $value * $tax_convert;
            break;
        case EnumMisureArray::$misure['pica']:
            $tax_convert = 16;
            return $value * $tax_convert;
            break;
        case EnumMisureArray::$misure['twip']:
            $tax_convert = 15;
            return $value * $tax_convert;
            break;
        default:
            return null;
            break;
        }
    }

    /**
     * Converte un valore pixel in una misura
     * @param type $misure unità di misura del valore da convertire (@see EnumMisureArray)
     * @param float $value valore da convertire (di default "1")
     * @return float pixel in misura
     */
    public static function convertPixelToMisure($misure, $value = 1) {
        switch ($misure) {
        case EnumMisureArray::$misure['meter']:
            $tax_convert = 3779.5275593333;
            return $value / $tax_convert;
            break;
        case EnumMisureArray::$misure['decimeter']:
            $tax_convert = 377.95275593333;
            return $value / $tax_convert;
            break;
        case EnumMisureArray::$misure['centimeter']:
            $tax_convert = 37.795275593333;
            return $value / $tax_convert;
            break;
        case EnumMisureArray::$misure['millimeter']:
            $tax_convert = 3.7795275593333;
            return $value / $tax_convert;
            break;
        case EnumMisureArray::$misure['point']:
            $tax_convert = 1.333333;
            return $value / $tax_convert;
            break;
        case EnumMisureArray::$misure['inches']:
            $tax_convert = 96;
            return $value / $tax_convert;
            break;
        case EnumMisureArray::$misure['feet']:
            $tax_convert = 1152;
            return $value / $tax_convert;
            break;
        case EnumMisureArray::$misure['pica']:
            $tax_convert = 16;
            return $value / $tax_convert;
            break;
        case EnumMisureArray::$misure['twip']:
            $tax_convert = 15;
            return $value / $tax_convert;
            break;
        default:
            return null;
            break;
        }
    }

    /**
     * Converte le dimensioni di un'immagine calcolando l'altezza relativa ad una larghezza voluta
     *
     * @param  string $url path del file immagine
     * @param  float|string $width dimensione della larghezza voluta
     * @return float altezza proporzionata alla larghezza
     */
    public static function convertImageSizeByWidth($url, $width) {
        list($w, $h) = getimagesize($url);
        return (($h * $width) / $w);
    }

    /**
     * Converte le dimensioni di un'immagine calcolando la larghezza relativa ad un'altezza voluta
     *
     * @param  string $url path del file immagine
     * @param  float|string $height dimensione dell'altezza voluta
     * @return float larghezza proporzionata alla altezza
     */
    public static function convertImageSizeByHeight($url, $height) {
        list($w, $h) = getimagesize($url);
        return (($w * $height) / $h);
    }
}
