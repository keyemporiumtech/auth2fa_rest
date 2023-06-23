<?php
App::uses("AppModel", "Model");

/**
 * Entity Eventreference
 *
 * @author Giuseppe Sassone
 *
 */
class Eventreference extends AppModel {
    public $arrayBelongsTo = array(
        'event_fk' => array(
            'className' => 'Event',
            'foreignKey' => 'event',
        ),
        'contactreference_fk' => array(
            'className' => 'Contactreference',
            'foreignKey' => 'contactreference',
        ),
        'tpcontactreference_fk' => array(
            'className' => 'Tpcontactreference',
            'foreignKey' => 'tpcontactreference',
        ),
    );
}
