<?php
App::uses("AppModel", "Model");

/**
 * Entity Activityattachment
 *
 * @author Giuseppe Sassone
 *
 */
class Activityattachment extends AppModel {
    public $arrayBelongsTo = array(
        'activity_fk' => array(
            'className' => 'Activity',
            'foreignKey' => 'activity',
        ),
        'attachment_fk' => array(
            'className' => 'Attachment',
            'foreignKey' => 'attachment',
        ),
        'tpattachment_fk' => array(
            'className' => 'Tpattachment',
            'foreignKey' => 'tpattachment',
        ),
    );
}
