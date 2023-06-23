<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Userreference", "Model");

class UserreferenceBS extends AppGenericBS {

    function __construct() {
        parent::__construct('Userreference');
    }

    function resetPrincipal($id_user, $type, $groups = null, $likegroups = null, $json = false) {
        $sql = "UPDATE userreferences SET flgprincipal=0 WHERE user=$id_user AND tpcontactreference=$type";

        parent::sqlConditionGroups($sql, $groups, $likegroups, $json);

        $this->execute($sql);
    }
}
