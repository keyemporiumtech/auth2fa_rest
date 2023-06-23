<?php
App::uses("AppModel", "Model");

/**
 * Entity Ticket
 * 
 * @author Giuseppe Sassone
 *
 */
class Ticket extends AppModel {
    public $arrayBelongsTo= array (
        'image_fk' => array (
                'className' => 'Attachment',
                'foreignKey' => 'image'
        ),
        'event_fk' => array (
                'className' => 'Event',
                'foreignKey' => 'event'
        ),
        'category_fk' => array (
                'className' => 'Category',
                'foreignKey' => 'category'
        ),
        'price_fk' => array (
                'className' => 'Price',
                'foreignKey' => 'price'
        )
);
}
