<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Userprofile", "Model");

class UserprofileBS extends AppGenericBS {

    function __construct() {
        parent::__construct('Userprofile');
    }

    function cleanProfile($id_profile) {
        $sql = "DELETE userprofiles WHERE profile=$id_profile";
        $this->execute($sql);
    }

    function filterUserActivity($id_activity) {
        $sql = "SELECT DISTINCT Userprofile.user FROM userprofiles as Userprofile WHERE 1";
        $sql .= " AND Userprofile.activity = {$id_activity}";
        return $this->query($sql, false);
    }
}
