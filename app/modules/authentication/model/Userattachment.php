<?php
App::uses("AppModel", "Model");

/**
 * Entity Userattachment
 *
 * @author Giuseppe Sassone
 *
 */
class Userattachment extends AppModel {
    public $arrayBelongsTo = array(
        'user_fk' => array(
            'className' => 'User',
            'foreignKey' => 'user',
        ),
        'attachment_fk' => array(
            'className' => 'Attachment',
            'foreignKey' => 'attachment',
        ),
        'tpattachment_fk' => array(
            'className' => 'Tpattachment',
            'foreignKey' => 'tpattachment',
        ),
    );
}
