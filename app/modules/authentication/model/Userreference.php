<?php
App::uses("AppModel", "Model");

/**
 * Entity Userreference
 *
 * @author Giuseppe Sassone
 *
 */
class Userreference extends AppModel {
    public $arrayBelongsTo = array(
        'user_fk' => array(
            'className' => 'User',
            'foreignKey' => 'user',
        ),
        'contactreference_fk' => array(
            'className' => 'Contactreference',
            'foreignKey' => 'contactreference',
        ),
        'tpcontactreference_fk' => array(
            'className' => 'Tpcontactreference',
            'foreignKey' => 'tpcontactreference',
        ),
    );
}
