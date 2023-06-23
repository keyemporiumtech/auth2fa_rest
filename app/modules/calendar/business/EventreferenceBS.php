<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Eventreference", "Model");

class EventreferenceBS extends AppGenericBS {

    function __construct() {
        parent::__construct('Eventreference');
    }

    function resetPrincipal($id_event, $type, $groups = null, $likegroups = null, $json = false) {
        $sql = "UPDATE eventreferences SET flgprincipal=0 WHERE event=$id_event AND tpcontactreference=$type";

        parent::sqlConditionGroups($sql, $groups, $likegroups, $json);

        $this->execute($sql);
    }
}
