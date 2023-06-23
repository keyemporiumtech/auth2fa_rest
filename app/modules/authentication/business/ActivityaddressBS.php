<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Activityaddress", "Model");

class ActivityaddressBS extends AppGenericBS {

    function __construct() {
        parent::__construct('Activityaddress');
    }

    function resetPrincipal($id_activity, $groups = null, $likegroups = null, $json = false) {
        $sql = "UPDATE activityaddresses SET flgprincipal=0 WHERE activity=$id_activity";

        parent::sqlConditionGroups($sql, $groups, $likegroups, $json);

        $this->execute($sql);
    }
}
