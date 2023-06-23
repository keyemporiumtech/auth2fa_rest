<?php
App::uses("AppModel", "Model");

/**
 * Entity Paymentmethod
 * 
 * @author Giuseppe Sassone
 *
 */
class Paymentmethod extends AppModel {
    public $arrayBelongsTo = array(
        'user_fk' => array(
            'className' => 'User',
            'foreignKey' => 'user',
        ),
        'activity_fk' => array(
            'className' => 'Activity',
            'foreignKey' => 'activity',
        ),
        'tppaymentmethod_fk' => array(
            'className' => 'Tppaymentmethod',
            'foreignKey' => 'tppaymentmethod',
        ),
        'tpwebpayment_fk' => array(
            'className' => 'Tpwebpayment',
            'foreignKey' => 'tpwebpayment',
        ),
    );
}
