<?php
App::uses("AppModel", "Model");

/**
 * Entity Activityreference
 *
 * @author Giuseppe Sassone
 *
 */
class Activityreference extends AppModel {
    public $arrayBelongsTo = array(
        'activity_fk' => array(
            'className' => 'Activity',
            'foreignKey' => 'activity',
        ),
        'contactreference_fk' => array(
            'className' => 'Contactreference',
            'foreignKey' => 'contactreference',
        ),
        'tpcontactreference_fk' => array(
            'className' => 'Tpcontactreference',
            'foreignKey' => 'tpcontactreference',
        ),
    );
}
