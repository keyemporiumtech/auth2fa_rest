<?php
App::uses("AppModel", "Model");

/**
 * Entity Basketproduct
 *
 * @author Giuseppe Sassone
 *
 */
class Basketproduct extends AppModel {
    public $arrayBelongsTo = array(
        'basket_fk' => array(
            'className' => 'Basket',
            'foreignKey' => 'basket',
        ),
        'product_fk' => array(
            'className' => 'Product',
            'foreignKey' => 'product',
        ),
    );
}
