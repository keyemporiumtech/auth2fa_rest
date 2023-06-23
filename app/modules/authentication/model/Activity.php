<?php
App::uses("AppModel", "Model");

/**
 * Entity Activity
 *
 * @author Giuseppe Sassone
 *
 */
class Activity extends AppModel {
    public $avoidUsertest = false;

    public $arrayBelongsTo = array(
        'tpactivity_fk' => array(
            'className' => 'Tpactivity',
            'foreignKey' => 'tpactivity',
        ),
        'tpcat_fk' => array(
            'className' => 'Tpcat',
            'foreignKey' => 'tpcat',
        ),
        'parent_fk' => array(
            'className' => 'Activity',
            'foreignKey' => 'parent_id',
        ),
    );

    public function beforeFind($query) {
        parent::beforeFind($query);
        if ($this->avoidUsertest) {
            $query['conditions'][$this->alias . '.flgtest'] = "0";
        }
        return $query;
    }
}
