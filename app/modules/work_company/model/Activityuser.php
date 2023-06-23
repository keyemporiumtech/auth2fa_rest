<?php
App::uses("AppModel", "Model");

/**
 * Entity Activityuser
 *
 * @author Giuseppe Sassone
 *
 */
class Activityuser extends AppModel {
    public $arrayBelongsTo = array(
        'activity_fk' => array(
            'className' => 'Activity',
            'foreignKey' => 'company',
        ),
        'user_fk' => array(
            'className' => 'User',
            'foreignKey' => 'user',
        ),
    );
}
