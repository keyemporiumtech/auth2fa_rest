<?php
App::uses("AppModel", "Model");

/**
 * Entity Productreservesetting
 *
 * @author Giuseppe Sassone
 *
 */
class Productreservesetting extends AppModel {
    public $arrayBelongsTo = array(
        'product_fk' => array(
            'className' => 'Product',
            'foreignKey' => 'product',
        ),
        'reservationsetting_fk' => array(
            'className' => 'Reservationsetting',
            'foreignKey' => 'settings',
        ),
    );
}
