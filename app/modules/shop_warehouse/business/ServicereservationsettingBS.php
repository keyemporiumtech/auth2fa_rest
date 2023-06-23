<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Servicereservesetting", "Model");

class ServicereservesettingBS extends AppGenericBS {

    function __construct() {
        parent::__construct('Servicereservesetting');
    }
}
