<?php
App::uses("AppModel", "Model");

/**
 * Entity Userrelationpermission
 *
 * @author Giuseppe Sassone
 *
 */
class Userrelationpermission extends AppModel {
    public $arrayBelongsTo = array(
        'userrelation_fk' => array(
            'className' => 'Userrelation',
            'foreignKey' => 'userrelation',
        ),
        'permission_fk' => array(
            'className' => 'Permission',
            'foreignKey' => 'permission',
        ),
    );
}
