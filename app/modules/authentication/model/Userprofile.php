<?php
App::uses("AppModel", "Model");

/**
 * Entity Userprofile
 *
 * @author Giuseppe Sassone
 *
 */
class Userprofile extends AppModel {
    public $arrayBelongsTo = array(
        'user_fk' => array(
            'className' => 'User',
            'foreignKey' => 'user',
        ),
        'profile_fk' => array(
            'className' => 'Profile',
            'foreignKey' => 'profile',
        ),
        'activity_fk' => array(
            'className' => 'Activity',
            'foreignKey' => 'activity',
        ),
    );
}
