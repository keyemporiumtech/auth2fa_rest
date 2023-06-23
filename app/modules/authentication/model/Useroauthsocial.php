<?php
App::uses("AppModel", "Model");

/**
 * Entity Useroauthsocial
 *
 * @author Giuseppe Sassone
 *
 */
class Useroauthsocial extends AppModel {
    public $arrayBelongsTo = array(
        'user_fk' => array(
            'className' => 'User',
            'foreignKey' => 'user',
        ),
        'tpsocialreference_fk' => array(
            'className' => 'Tpsocialreference',
            'foreignKey' => 'tpsocialreference',
        ),
    );
}
