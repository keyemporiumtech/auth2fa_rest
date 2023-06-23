<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Activityattachment", "Model");

class ActivityattachmentBS extends AppGenericBS {

    function __construct() {
        parent::__construct('Activityattachment');
    }

    function resetPrincipal($id_activity, $type, $groups = null, $likegroups = null, $json = false) {
        $sql = "UPDATE activityattachments SET flgprincipal=0 WHERE activity=$id_activity AND tpattachment=$type";

        parent::sqlConditionGroups($sql, $groups, $likegroups, $json);

        $this->execute($sql);
    }
}
