<?php
App::uses("ArrayUtility", "modules/coreutils/utility");
class IBANModel {
    public $input = "";
    public $iban = "";
    public $bban = "";
    public $swift_bic = "";
    public $swift = "";
    public $bic = "";
    public $abi = "";
    public $cab = "";
    public $cin = "";
    public $cc = "";
    public $bankcod = "";
    public $bankorg = ""; /* codice filiale/codice sportello/codice agenzia/Code guichet */
    public $labelorg_cod = ""; /* EnumIBANOrg */
    public $controlkey = ""; /* chiave di controllo */
    public $controlkey2 = ""; /* seconda chiave di controllo */
    public $controlnumbers = ""; /* numeri di controllo */
    public $controlcod = ""; /* codice di controllo */
    public $id_national_owner = ""; /* id nazionale del proprietario */
    public $cod_iso3166 = "";
    public $pattern = ""; /* FIELD1-INITSTRING-ENDSTRING|FIELD2-INITSTRING-ENDSTRING */
    public $labelpattern = ""; /* FIELD1-KEY_TRANSLATE|FIELD2-KEY_TRANSLATE*/
    public $length = 0;
    public $labels = array();

    function __construct($iban = null) {
        $this->iban = $iban;
    }

    function printFormat($separator = " ") {
        $arr = array();
        foreach ($this->labels as $key => $value) {
            array_push($arr, $value . ":" . $this->{$key});
        }
        return ArrayUtility::getStringByList($arr, false, $separator);
    }
}