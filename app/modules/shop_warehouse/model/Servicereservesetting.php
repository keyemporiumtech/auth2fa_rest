<?php
App::uses("AppModel", "Model");

/**
 * Entity Servicereservesetting
 *
 * @author Giuseppe Sassone
 *
 */
class Servicereservesetting extends AppModel {
    public $arrayBelongsTo = array(
        'service_fk' => array(
            'className' => 'Service',
            'foreignKey' => 'service',
        ),
        'reservationsetting_fk' => array(
            'className' => 'Reservationsetting',
            'foreignKey' => 'settings',
        ),
    );
}
