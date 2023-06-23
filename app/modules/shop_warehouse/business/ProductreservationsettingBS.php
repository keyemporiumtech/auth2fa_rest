<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Productreservesetting", "Model");

class ProductreservesettingBS extends AppGenericBS {

    function __construct() {
        parent::__construct('Productreservesetting');
    }
}
