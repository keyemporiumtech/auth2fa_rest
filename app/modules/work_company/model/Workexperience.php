<?php
App::uses("AppModel", "Model");

/**
 * Entity Workexperience
 *
 * @author Giuseppe Sassone
 *
 */
class Workexperience extends AppModel {
    public $arrayBelongsTo = array(
        'activity_fk' => array(
            'className' => 'Activity',
            'foreignKey' => 'company',
        ),
        'city_fk' => array(
            'className' => 'City',
            'foreignKey' => 'city',
        ),
        'workrole_fk' => array(
            'className' => 'Workrole',
            'foreignKey' => 'role',
        ),
        'nation_fk' => array(
            'className' => 'Nation',
            'foreignKey' => 'nation',
        ),
    );

    public $arrayVirtualFields = array(
        'nation_val' => "SELECT name FROM nations as Nation WHERE Nation.id = Workexperience.nation",
        'nation_cod' => "SELECT cod_iso3166 FROM nations as Nation WHERE Nation.id = Workexperience.nation",
        'city_val' => "SELECT place FROM cities as City WHERE City.id = Workexperience.city",
    );
}
