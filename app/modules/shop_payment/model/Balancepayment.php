<?php
App::uses("AppModel", "Model");

/**
 * Entity Balancepayment
 *
 * @author Giuseppe Sassone
 *
 */
class Balancepayment extends AppModel {
    public $arrayBelongsTo = array(
        'balance_fk' => array(
            'className' => 'Balance',
            'foreignKey' => 'balance',
        ),
        'payment_fk' => array(
            'className' => 'Payment',
            'foreignKey' => 'payment',
        ),
    );
}
