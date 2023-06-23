<?php
App::uses("AppModel", "Model");

/**
 * Entity Profilepermission
 *
 * @author Giuseppe Sassone
 *
 */
class Profilepermission extends AppModel {
    public $arrayBelongsTo = array(
        'profile_fk' => array(
            'className' => 'Profile',
            'foreignKey' => 'profile',
        ),
        'permission_fk' => array(
            'className' => 'Permission',
            'foreignKey' => 'permission',
        ),
    );
}
