<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Useraddress", "Model");

class UseraddressBS extends AppGenericBS {

    function __construct() {
        parent::__construct('Useraddress');
    }

    function resetPrincipal($id_user, $groups = null, $likegroups = null, $json = false) {
        $sql = "UPDATE useraddresses SET flgprincipal=0 WHERE user=$id_user";

        parent::sqlConditionGroups($sql, $groups, $likegroups, $json);

        $this->execute($sql);
    }
}
