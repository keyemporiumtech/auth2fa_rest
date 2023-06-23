<?php
App::uses("AppModel", "Model");

/**
 * Entity Basket
 *
 * @author Giuseppe Sassone
 *
 */
class Basket extends AppModel {
    public $arrayBelongsTo = array(
        'user_fk' => array(
            'className' => 'User',
            'foreignKey' => 'user',
        ),
        'activity_fk' => array(
            'className' => 'Activity',
            'foreignKey' => 'activity',
        ),
    );
}
