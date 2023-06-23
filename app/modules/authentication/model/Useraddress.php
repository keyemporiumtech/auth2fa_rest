<?php
App::uses("AppModel", "Model");

/**
 * Entity Useraddress
 *
 * @author Giuseppe Sassone
 *
 */
class Useraddress extends AppModel {
    public $arrayBelongsTo = array(
        'user_fk' => array(
            'className' => 'User',
            'foreignKey' => 'user',
        ),
        'address_fk' => array(
            'className' => 'Address',
            'foreignKey' => 'address',
        ),
        'tpaddress_fk' => array(
            'className' => 'Tpaddress',
            'foreignKey' => 'tpaddress',
        ),
    );
}
