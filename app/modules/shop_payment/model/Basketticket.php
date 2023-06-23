<?php
App::uses("AppModel", "Model");

/**
 * Entity Basketticket
 *
 * @author Giuseppe Sassone
 *
 */
class Basketticket extends AppModel {
    public $arrayBelongsTo = array(
        'basket_fk' => array(
            'className' => 'Basket',
            'foreignKey' => 'basket',
        ),
        'ticket_fk' => array(
            'className' => 'Ticket',
            'foreignKey' => 'ticket',
        ),
    );
}
