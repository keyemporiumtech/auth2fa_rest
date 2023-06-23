<?php
App::uses("Codes", "Config/system");
App::uses("IBANAlbania", "modules/validator_iban/classes/IBAN");
App::uses("IBANAndorra", "modules/validator_iban/classes/IBAN");
App::uses("IBANAustria", "modules/validator_iban/classes/IBAN");
App::uses("IBANArabiaSaudita", "modules/validator_iban/classes/IBAN");
App::uses("IBANAzerbaigian", "modules/validator_iban/classes/IBAN");
App::uses("IBANBahrein", "modules/validator_iban/classes/IBAN");
App::uses("IBANBelgio", "modules/validator_iban/classes/IBAN");
App::uses("IBANBosniaErzegovina", "modules/validator_iban/classes/IBAN");
App::uses("IBANBulgaria", "modules/validator_iban/classes/IBAN");
App::uses("IBANCostaRica", "modules/validator_iban/classes/IBAN");
App::uses("IBANCroazia", "modules/validator_iban/classes/IBAN");
App::uses("IBANCipro", "modules/validator_iban/classes/IBAN");
App::uses("IBANDanimarca", "modules/validator_iban/classes/IBAN");
App::uses("IBANEstonia", "modules/validator_iban/classes/IBAN");
App::uses("IBANFinlandia", "modules/validator_iban/classes/IBAN");
App::uses("IBANFrancia", "modules/validator_iban/classes/IBAN");
App::uses("IBANGeorgia", "modules/validator_iban/classes/IBAN");
App::uses("IBANGermania", "modules/validator_iban/classes/IBAN");
App::uses("IBANGibilterra", "modules/validator_iban/classes/IBAN");
App::uses("IBANGrecia", "modules/validator_iban/classes/IBAN");
App::uses("IBANGroenlandia", "modules/validator_iban/classes/IBAN");
App::uses("IBANIrlanda", "modules/validator_iban/classes/IBAN");
App::uses("IBANIslanda", "modules/validator_iban/classes/IBAN");
App::uses("IBANIsoleFaroe", "modules/validator_iban/classes/IBAN");
App::uses("IBANIsolaMaurizio", "modules/validator_iban/classes/IBAN");
App::uses("IBANIsraele", "modules/validator_iban/classes/IBAN");
App::uses("IBANItalia", "modules/validator_iban/classes/IBAN");
App::uses("IBANKazakistan", "modules/validator_iban/classes/IBAN");
App::uses("IBANKuwait", "modules/validator_iban/classes/IBAN");
App::uses("IBANKosovo", "modules/validator_iban/classes/IBAN");
App::uses("IBANLettonia", "modules/validator_iban/classes/IBAN");
App::uses("IBANLibano", "modules/validator_iban/classes/IBAN");
App::uses("IBANLiechtenstein", "modules/validator_iban/classes/IBAN");
App::uses("IBANLituania", "modules/validator_iban/classes/IBAN");
App::uses("IBANLussemburgo", "modules/validator_iban/classes/IBAN");
App::uses("IBANMacedoniaNord", "modules/validator_iban/classes/IBAN");
App::uses("IBANMalta", "modules/validator_iban/classes/IBAN");
App::uses("IBANMauritania", "modules/validator_iban/classes/IBAN");
App::uses("IBANMoldavia", "modules/validator_iban/classes/IBAN");
App::uses("IBANMontenegro", "modules/validator_iban/classes/IBAN");
App::uses("IBANNorvegia", "modules/validator_iban/classes/IBAN");
App::uses("IBANPaesiBassi", "modules/validator_iban/classes/IBAN");
App::uses("IBANPolonia", "modules/validator_iban/classes/IBAN");
App::uses("IBANPortogallo", "modules/validator_iban/classes/IBAN");
App::uses("IBANPrincipatoMonaco", "modules/validator_iban/classes/IBAN");
App::uses("IBANRegnoUnito", "modules/validator_iban/classes/IBAN");
App::uses("IBANRepubblicaCeca", "modules/validator_iban/classes/IBAN");
App::uses("IBANRepubblicaDominicana", "modules/validator_iban/classes/IBAN");
App::uses("IBANRepubblicaSanMarino", "modules/validator_iban/classes/IBAN");
App::uses("IBANRomania", "modules/validator_iban/classes/IBAN");
App::uses("IBANSerbia", "modules/validator_iban/classes/IBAN");
App::uses("IBANSlovacchia", "modules/validator_iban/classes/IBAN");
App::uses("IBANSlovenia", "modules/validator_iban/classes/IBAN");
App::uses("IBANSpagna", "modules/validator_iban/classes/IBAN");
App::uses("IBANSvezia", "modules/validator_iban/classes/IBAN");
App::uses("IBANSvizzera", "modules/validator_iban/classes/IBAN");
App::uses("IBANTurchia", "modules/validator_iban/classes/IBAN");
App::uses("IBANTunisia", "modules/validator_iban/classes/IBAN");
App::uses("IBANUngheria", "modules/validator_iban/classes/IBAN");

class IBANUtility {

    static function getAvailableNations() {
        return array('AL', 'AD', 'AT', 'SA', 'AZ', 'BH', 'BE', 'BA', 'BG', 'CR', 'HR', 'CY', 'DK', 'EE', 'FI', 'FR', 'GE', 'DE', 'GI', 'GR', 'GL',
            'IE', 'IS', 'FO', 'MU', 'IL', 'IT', 'KZ', 'KW', 'XK', 'LV', 'LB', 'LI', 'LT', 'LU', 'MK', 'MT', 'MR', 'MD', 'ME', 'NO', 'NL', 'PL', 'PT',
            'MC', 'GB', 'CZ', 'DO', 'SM', 'RO', 'RS', 'SK', 'SI', 'ES', 'SE', 'CH', 'TR', 'TN', 'HU');
    }
    static function getIBANModel($iban, $nationcod) {
        $input = $iban;
        if (empty($iban)) {
            $iban = IBANUtility::getExampleIBAN($nationcod);
        }
        try {
            $model = null;
            switch (strtoupper($nationcod)) {
            case 'AL':
                $model = IBANAlbania::converter($iban);
                break;
            case 'AD':
                $model = IBANAndorra::converter($iban);
                break;
            case 'AT':
                $model = IBANAustria::converter($iban);
                break;
            case 'SA':
                $model = IBANArabiaSaudita::converter($iban);
                break;
            case 'AZ':
                $model = IBANAzerbaigian::converter($iban);
                break;
            case 'BH':
                $model = IBANBahrein::converter($iban);
                break;
            case 'BE':
                $model = IBANBelgio::converter($iban);
                break;
            case 'BA':
                $model = IBANBosniaErzegovina::converter($iban);
                break;
            case 'BG':
                $model = IBANBulgaria::converter($iban);
                break;
            case 'CR':
                $model = IBANCostaRica::converter($iban);
                break;
            case 'HR':
                $model = IBANCroazia::converter($iban);
                break;
            case 'CY':
                $model = IBANCipro::converter($iban);
                break;
            case 'DK':
                $model = IBANDanimarca::converter($iban);
                break;
            case 'EE':
                $model = IBANEstonia::converter($iban);
                break;
            case 'FI':
                $model = IBANFinlandia::converter($iban);
                break;
            case 'FR':
                $model = IBANFrancia::converter($iban);
                break;
            case 'GE':
                $model = IBANGeorgia::converter($iban);
                break;
            case 'DE':
                $model = IBANGermania::converter($iban);
                break;
            case 'GI':
                $model = IBANGibilterra::converter($iban);
                break;
            case 'GR':
                $model = IBANGrecia::converter($iban);
                break;
            case 'GL':
                $model = IBANGroenlandia::converter($iban);
                break;
            case 'IE':
                $model = IBANIrlanda::converter($iban);
                break;
            case 'IS':
                $model = IBANIslanda::converter($iban);
                break;
            case 'FO':
                $model = IBANIsoleFaroe::converter($iban);
                break;
            case 'MU':
                $model = IBANIsolaMaurizio::converter($iban);
                break;
            case 'IL':
                $model = IBANIsraele::converter($iban);
                break;
            case 'IT':
                $model = IBANItalia::converter($iban);
                break;
            case 'KZ':
                $model = IBANKazakistan::converter($iban);
                break;
            case 'KW':
                $model = IBANKuwait::converter($iban);
                break;
            case 'XK':
                $model = IBANKosovo::converter($iban);
                break;
            case 'LV':
                $model = IBANLettonia::converter($iban);
                break;
            case 'LB':
                $model = IBANLibano::converter($iban);
                break;
            case 'LI':
                $model = IBANLiechtenstein::converter($iban);
                break;
            case 'LT':
                $model = IBANLituania::converter($iban);
                break;
            case 'LU':
                $model = IBANLussemburgo::converter($iban);
                break;
            case 'MK':
                $model = IBANMacedoniaNord::converter($iban);
                break;
            case 'MT':
                $model = IBANMalta::converter($iban);
                break;
            case 'MR':
                $model = IBANMauritania::converter($iban);
                break;
            case 'MD':
                $model = IBANMoldavia::converter($iban);
                break;
            case 'ME':
                $model = IBANMontenegro::converter($iban);
                break;
            case 'NO':
                $model = IBANNorvegia::converter($iban);
                break;
            case 'NL':
                $model = IBANPaesiBassi::converter($iban);
                break;
            case 'PL':
                $model = IBANPolonia::converter($iban);
                break;
            case 'PT':
                $model = IBANPortogallo::converter($iban);
                break;
            case 'MC':
                $model = IBANPrincipatoMonaco::converter($iban);
                break;
            case 'GB':
                $model = IBANRegnoUnito::converter($iban);
                break;
            case 'CZ':
                $model = IBANRepubblicaCeca::converter($iban);
                break;
            case 'DO':
                $model = IBANRepubblicaDominicana::converter($iban);
                break;
            case 'SM':
                $model = IBANRepubblicaSanMarino::converter($iban);
                break;
            case 'RO':
                $model = IBANRomania::converter($iban);
                break;
            case 'RS':
                $model = IBANSerbia::converter($iban);
                break;
            case 'SK':
                $model = IBANSlovacchia::converter($iban);
                break;
            case 'SI':
                $model = IBANSlovenia::converter($iban);
                break;
            case 'ES':
                $model = IBANSpagna::converter($iban);
                break;
            case 'SE':
                $model = IBANSvezia::converter($iban);
                break;
            case 'CH':
                $model = IBANSvizzera::converter($iban);
                break;
            case 'TR':
                $model = IBANTurchia::converter($iban);
                break;
            case 'TN':
                $model = IBANTunisia::converter($iban);
                break;
            case 'HU':
                $model = IBANUngheria::converter($iban);
                break;
            default:
                throw new Exception("IBAN for nation " . $nationcod . " not found", Codes::get('ERROR_VALIDATOR_IBAN'));
            }
            $model->input = $input;
            return $model;
        } catch (Exception $e) {
            throw ($e);
        }
    }

    static function getExampleIBAN($cod_iso3166) {
        switch ($cod_iso3166) {
        case 'AL':
            return 'ALkk BBBB SSSK CCCC CCCC CCCC CCCC';
        case 'AD':
            return 'ADkk BBBB SSSS CCCC CCCC CCCC';
        case 'SA':
            return 'SAkk BBCC CCCC CCCC CCCC CCCC';
        case 'AT':
            return 'ATkk BBBB BCCC CCCC CCCC';
        case 'AZ':
            return 'AZkk 12345 12345678901234567890';
        case 'BH':
            return 'BHkk ABCD 12345678901234';
        case 'BE':
            return 'BEkk BBBC CCCC CCKK';
        case 'BA':
            return 'BAkk BBBS SSCC CCCC CoKK';
        case 'BG':
            return 'BGkk BBBB SSSS DDCC CCCC CC';
        case 'CR':
            return 'CRkk 123 12345678901234';
        case 'HR':
            return 'HRkk BBBB BBBC CCCC CCCC C';
        case 'CY':
            return 'CYkk BBBS SSSS CCCC CCCC CCCC CCCC';
        case 'DK':
            return 'DKkk BBBB CCCC CCCC CC';
        case 'EE':
            return 'EEkk BBSS CCCC CCCC CCCK';
        case 'FI':
            return 'FIkk BBBB BBCC CCCC CK';
        case 'FR':
            return 'FRkk BBBB BGGG GGCC CCCC CCCC CKK';
        case 'GE':
            return 'GEkk BBCC CCCC CCCC CCCC CC';
        case 'DE':
            return 'DEkk BBBB BBBB CCCC CCCC CC';
        case 'GI':
            return 'GIkk BBBB CCCC CCCC CCCC CCC';
        case 'GR':
            return 'GRkk BBB BBBB CCCC CCCC CCCC CCCC';
        case 'GL':
            return 'GLkk BBBB CCCC CCCC CC';
        case 'IE':
            return 'IEkk AAAA BBBB BBCC CCCC CC';
        case 'IS':
            return 'ISkk BBBB SSCC CCCC XXXX XXXX XX';
        case 'FO':
            return 'FOkk BBBB CCCC CCCC CC';
        case 'MU':
            return 'MUkk BBBB BBSS CCCC CCCC CCCC CCCC CC';
        case 'IL':
            return 'ILkk BBB NNN CCCCCCCCCCCCC';
        case 'IT':
            return 'ITkk ABBB BBCC CCCX XXXX XXXX XXX';
        case 'KZ':
            return 'KZkk 123 1234567890123';
        case 'KW':
            return 'KWkk ABCD 1234567890123456789012';
        case 'XK':
            return 'XKkk 12 12345678901234';
        case 'LV':
            return 'LVkk BBBB CCCC CCCC CCCC C';
        case 'LB':
            return 'LBkk BBBB CCCC CCCC CCCC CCCC CCCC';
        case 'LI':
            return 'LIkk BBBB BCCC CCCC CCCC C';
        case 'LT':
            return 'LTkk BBBB BCCC CCCC CCCC';
        case 'LU':
            return 'LUkk BBBC CCCC CCCC CCCC';
        case 'MK':
            return 'MKkk BBBC CCCC CCCC CKK';
        case 'MT':
            return 'MTkk BBBB SSSS SCCC CCCC CCCC CCCC CCC';
        case 'MR':
            return 'MRkk 12345 12345 12345678901 12';
        case 'MD':
            return 'MDkk 12 123456789012345678';
        case 'ME':
            return 'MEkk BBBC CCCC CCCC CCCC KK';
        case 'NO':
            return 'NOkk BBBB CCCC CCK';
        case 'NL':
            return 'NLkk BBBB CCCC CCCC CC';
        case 'PL':
            return 'PLkk BBBB BBBk CCCC CCCC CCCC CCCC';
        case 'PT':
            return 'PTkk BBBB BBBB CCCC CCCC CCCK K';
        case 'MC':
            return 'MCkk BBBB BGGG GGCC CCCC CCCC CKK';
        case 'GB':
            return 'GBkk BBBB SSSS SSCC CCCC CC';
        case 'CZ':
            return 'CZkk BBBB SSSS SSCC CCCC CCCC';
        case 'DO':
            return 'DOkk BBBB 12345678901234567890';
        case 'SM':
            return 'SMkk ABBB BBCC CCCX XXXX XXXX XXX';
        case 'RO':
            return 'ROkk BBBB CCCC CCCC CCCC CCCC';
        case 'RS':
            return 'RSkk BBBC CCCC CCCC CCCC KK';
        case 'SK':
            return 'SKkk BBBB SSSS SSCC CCCC CCCC';
        case 'SI':
            return 'SIkk BBBB BCCC CCCC CKK';
        case 'ES':
            return 'ESkk BBBB GGGG KKCC CCCC CCCC';
        case 'SE':
            return 'SEkk BBB CCCC CCCC CCCC CCCC C';
        case 'CH':
            return 'CHkk BBBB BCCC CCCC CCCC C';
        case 'TR':
            return 'TRkk BBBB BRCC CCCC CCCC CCCC CC';
        case 'TN':
            return 'TNkk BBBB BCCC CCCC CCCC CCCC';
        case 'HU':
            return 'HUkk BBBB BBBC CCCC CCCC CCCC CCCC';
        default:
            return null;
        }
    }
}
