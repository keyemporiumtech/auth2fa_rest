<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Eventattachment", "Model");

class EventattachmentBS extends AppGenericBS {

    function __construct() {
        parent::__construct('Eventattachment');
    }

    function resetPrincipal($id_event, $type, $groups = null, $likegroups = null, $json = false) {
        $sql = "UPDATE eventattachments SET flgprincipal=0 WHERE event=$id_event AND tpattachment=$type";

        parent::sqlConditionGroups($sql, $groups, $likegroups, $json);

        $this->execute($sql);
    }
}
