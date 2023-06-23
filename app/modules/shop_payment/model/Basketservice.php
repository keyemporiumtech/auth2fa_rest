<?php
App::uses("AppModel", "Model");

/**
 * Entity Basketservice
 *
 * @author Giuseppe Sassone
 *
 */
class Basketservice extends AppModel {
    public $arrayBelongsTo = array(
        'basket_fk' => array(
            'className' => 'Basket',
            'foreignKey' => 'basket',
        ),
        'service_fk' => array(
            'className' => 'Service',
            'foreignKey' => 'service',
        ),
    );
}
