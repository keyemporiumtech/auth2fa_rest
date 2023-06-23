<?php
App::uses("AppModel", "Model");

/**
 * Entity Activityaddress
 *
 * @author Giuseppe Sassone
 *
 */
class Activityaddress extends AppModel {
    public $arrayBelongsTo = array(
        'activity_fk' => array(
            'className' => 'Activity',
            'foreignKey' => 'activity',
        ),
        'address_fk' => array(
            'className' => 'Address',
            'foreignKey' => 'address',
        ),
        'tpaddress_fk' => array(
            'className' => 'Tpaddress',
            'foreignKey' => 'tpaddress',
        ),
    );
}
