<?php
App::uses("AppModel", "Model");

/**
 * Entity Activityprofile
 *
 * @author Giuseppe Sassone
 *
 */
class Activityprofile extends AppModel {
    public $arrayBelongsTo = array(
        'activity_fk' => array(
            'className' => 'Activity',
            'foreignKey' => 'activity',
        ),
        'profile_fk' => array(
            'className' => 'Profile',
            'foreignKey' => 'profile',
        ),
    );
}
