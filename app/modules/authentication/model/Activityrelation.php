<?php
App::uses("AppModel", "Model");

/**
 * Entity Activityrelation
 *
 * @author Giuseppe Sassone
 *
 */
class Activityrelation extends AppModel {
    public $arrayBelongsTo = array(
        'user_fk' => array(
            'className' => 'User',
            'foreignKey' => 'user',
        ),
        'activity_fk' => array(
            'className' => 'Activity',
            'foreignKey' => 'activity',
        ),
        'tprelation_fk' => array(
            'className' => 'Tpactivityrelation',
            'foreignKey' => 'tprelation',
        ),
    );
}
