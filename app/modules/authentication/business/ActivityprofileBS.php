<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Activityprofile", "Model");

class ActivityprofileBS extends AppGenericBS {

    function __construct() {
        parent::__construct('Activityprofile');
    }

    function cleanProfile($id_profile) {
        $sql = "DELETE activityprofiles WHERE profile=$id_profile";
        $this->execute($sql);
    }
}
