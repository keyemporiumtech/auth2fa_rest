<?php
App::uses("AppModel", "Model");

/**
 * Entity Ticketdiscount
 *
 * @author Giuseppe Sassone
 *
 */
class Ticketdiscount extends AppModel {
    public $arrayBelongsTo = array(
        'ticket_fk' => array(
            'className' => 'Ticket',
            'foreignKey' => 'ticket',
        ),
        'discount_fk' => array(
            'className' => 'Discount',
            'foreignKey' => 'discount',
        ),
    );
}
