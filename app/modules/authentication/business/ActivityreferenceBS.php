<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Activityreference", "Model");

class ActivityreferenceBS extends AppGenericBS {

    function __construct() {
        parent::__construct('Activityreference');
    }

    function resetPrincipal($id_activity, $type, $groups = null, $likegroups = null, $json = false) {
        $sql = "UPDATE activityreferences SET flgprincipal=0 WHERE activity=$id_activity AND tpcontactreference=$type";

        parent::sqlConditionGroups($sql, $groups, $likegroups, $json);

        $this->execute($sql);
    }
}
