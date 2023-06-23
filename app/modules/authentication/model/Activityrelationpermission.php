<?php
App::uses("AppModel", "Model");

/**
 * Entity Activityrelationpermission
 *
 * @author Giuseppe Sassone
 *
 */
class Activityrelationpermission extends AppModel {
    public $arrayBelongsTo = array(
        'activityrelation_fk' => array(
            'className' => 'Activityrelation',
            'foreignKey' => 'activityrelation',
        ),
        'permission_fk' => array(
            'className' => 'Permission',
            'foreignKey' => 'permission',
        ),
    );
}
