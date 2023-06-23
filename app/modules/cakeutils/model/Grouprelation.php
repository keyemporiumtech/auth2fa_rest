<?php
App::uses("AppModel", "Model");

/**
 * Entity Grouprelation
 *
 * @author Giuseppe Sassone
 *
 */
class Grouprelation extends AppModel {
    public $arrayBelongsTo = array(
        'group_fk' => array(
            'className' => 'Group',
            'foreignKey' => 'group',
        ),
    );
}
