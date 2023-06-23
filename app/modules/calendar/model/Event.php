<?php
App::uses("AppModel", "Model");

/**
 * Entity Event
 *
 * @author Giuseppe Sassone
 *
 */
class Event extends AppModel {
    public $arrayBelongsTo = array(
        'tpevent_fk' => array(
            'className' => 'Tpevent',
            'foreignKey' => 'tpevent',
        ),
        'tpcat_fk' => array(
            'className' => 'Tpcat',
            'foreignKey' => 'tpcat',
        ),
    );
}
