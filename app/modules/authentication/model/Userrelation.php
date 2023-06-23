<?php
App::uses("AppModel", "Model");

/**
 * Entity Userrelation
 *
 * @author Giuseppe Sassone
 *
 */
class Userrelation extends AppModel {
    public $arrayBelongsTo = array(
        'user1_fk' => array(
            'className' => 'User',
            'foreignKey' => 'user1',
        ),
        'user2_fk' => array(
            'className' => 'User',
            'foreignKey' => 'user2',
        ),
        'tprelation_fk' => array(
            'className' => 'Tpuserrelation',
            'foreignKey' => 'tprelation',
        ),
    );
}
