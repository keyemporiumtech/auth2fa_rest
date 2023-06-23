<?php
App::uses("AppModel", "Model");

/**
 * Entity Ticketattachment
 *
 * @author Giuseppe Sassone
 *
 */
class Ticketattachment extends AppModel {
    public $arrayBelongsTo = array(
        'ticket_fk' => array(
            'className' => 'Ticket',
            'foreignKey' => 'ticket',
        ),
        'attachment_fk' => array(
            'className' => 'Attachment',
            'foreignKey' => 'attachment',
        ),
    );
}
