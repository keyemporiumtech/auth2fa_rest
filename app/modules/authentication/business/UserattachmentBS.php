<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("Userattachment", "Model");

class UserattachmentBS extends AppGenericBS {

    function __construct() {
        parent::__construct('Userattachment');
    }

    function resetPrincipal($id_user, $type, $groups = null, $likegroups = null, $json = false) {
        $sql = "UPDATE userattachments SET flgprincipal=0 WHERE user=$id_user AND tpattachment=$type";

        parent::sqlConditionGroups($sql, $groups, $likegroups, $json);

        $this->execute($sql);
    }
}
