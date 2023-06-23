<?php
App::uses("AppModel", "Model");

/**
 * Entity Eventattachment
 *
 * @author Giuseppe Sassone
 *
 */
class Eventattachment extends AppModel {
    public $arrayBelongsTo = array(
        'event_fk' => array(
            'className' => 'Event',
            'foreignKey' => 'event',
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
