<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Profilepermission", "Model");

class ProfilepermissionBS extends AppGenericBS {

    function __construct() {
        parent::__construct('Profilepermission');
    }

    function cleanProfilepermissions($id_profile) {
        $sql = "DELETE profilepermissions WHERE profile=$id_profile";
        $this->execute($sql);
    }
}
