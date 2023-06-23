<?php
App::uses("AppModel", "Model");

/**
 * Entity Payment
 *
 * @author Giuseppe Sassone
 *
 */
class Payment extends AppModel {
    public $arrayBelongsTo = array(
        'paymentmethod_fk' => array(
            'className' => 'Paymentmethod',
            'foreignKey' => 'paymentmethod',
        ),
        'tppayment_fk' => array(
            'className' => 'Tppayment',
            'foreignKey' => 'tppayment',
        ),
        'price_fk' => array(
            'className' => 'Price',
            'foreignKey' => 'price',
        ),
    );

    public $arrayVirtualFields = array(
        'balance_id' => "SELECT Balancepayment.balance FROM balancepayments as Balancepayment WHERE Balancepayment.payment=Payment.id",
    );
}
