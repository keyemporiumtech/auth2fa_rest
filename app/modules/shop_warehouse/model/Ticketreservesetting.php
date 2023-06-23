<?php
App::uses("AppModel", "Model");

/**
 * Entity Ticketreservesetting
 *
 * @author Giuseppe Sassone
 *
 */
class Ticketreservesetting extends AppModel {
    public $arrayBelongsTo = array(
        'ticket_fk' => array(
            'className' => 'Ticket',
            'foreignKey' => 'ticket',
        ),
        'reservationsetting_fk' => array(
            'className' => 'Reservationsetting',
            'foreignKey' => 'settings',
        ),
    );
}
