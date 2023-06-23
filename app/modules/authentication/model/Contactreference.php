<?php
App::uses("AppModel", "Model");
App::uses("Nation", "Model");
App::uses("Tpcontactreference", "Model");
App::uses("Tpsocialreference", "Model");
App::uses("FileUtility", "modules/coreutils/utility");

/**
 * Entity Contactreference
 *
 * @author Giuseppe Sassone
 *
 */
class Contactreference extends AppModel {
    public $avoidContent = false;
    public $arrayBelongsTo = array(
        'tpcontactreference_fk' => array(
            'className' => 'Tpcontactreference',
            'foreignKey' => 'tpcontactreference',
        ),
        'tpsocialreference_fk' => array(
            'className' => 'Tpsocialreference',
            'foreignKey' => 'tpsocialreference',
        ),
    );

    public function afterFind($results, $primary = false) {
        $this->setImageMapping($results);
        return parent::afterFind($results, $primary);
    }

    public function setImageMapping(&$data) {
        foreach ($data as &$obj) {
            if (!$this->avoidContent) {
                $nationModel = null;
                if (array_key_exists($this->alias, $obj) && !empty($obj[$this->alias]['prefix'])) {
                    $nation = new Nation();
                    $nationModel = $nation->find('first', array(
                        'conditions' => array(
                            'tel' => $obj[$this->alias]['prefix'],
                        ),
                    ));
                }
                if (!empty($nationModel) && array_key_exists('Nation', $nationModel) && array_key_exists('symbol', $nationModel['Nation'])) {
                    $url = WWW_ROOT . "img" . DS . "nations" . DS . "" . $nationModel['Nation']['symbol'];
                    $obj[$this->alias]['nationimage'] = "data: " . mime_content_type($url) . ";base64," . FileUtility::getBaseContentByPath($url);
                }

                $tpcontactreferenceModel = null;
                if (array_key_exists($this->alias, $obj) && !empty($obj[$this->alias]['tpcontactreference'])) {
                    $tpcontactreference = new Tpcontactreference();
                    $tpcontactreferenceModel = $tpcontactreference->find('first', array(
                        'conditions' => array(
                            'id' => $obj[$this->alias]['tpcontactreference'],
                        ),
                    ));
                }
                if (!empty($tpcontactreferenceModel) && array_key_exists('Tpcontactreference', $tpcontactreferenceModel) && array_key_exists('symbol', $tpcontactreferenceModel['Tpcontactreference'])) {
                    $url = WWW_ROOT . "img" . DS . "contactreference" . DS . "" . $tpcontactreferenceModel['Tpcontactreference']['symbol'];
                    $obj[$this->alias]['referenceimage'] = "data: " . mime_content_type($url) . ";base64," . FileUtility::getBaseContentByPath($url);
                }

                $tpsocialreferenceModel = null;
                if (array_key_exists($this->alias, $obj) && !empty($obj[$this->alias]['tpsocialreference'])) {
                    $tpsocialreference = new Tpsocialreference();
                    $tpsocialreferenceModel = $tpsocialreference->find('first', array(
                        'conditions' => array(
                            'id' => $obj[$this->alias]['tpsocialreference'],
                        ),
                    ));
                }
                if (!empty($tpsocialreferenceModel) && array_key_exists('Tpsocialreference', $tpsocialreferenceModel) && array_key_exists('symbol', $tpsocialreferenceModel['Tpsocialreference'])) {
                    $url = WWW_ROOT . "img" . DS . "socialreference" . DS . "" . $tpsocialreferenceModel['Tpsocialreference']['symbol'];
                    $obj[$this->alias]['socialimage'] = "data: " . mime_content_type($url) . ";base64," . FileUtility::getBaseContentByPath($url);
                }
            }
        }
    }
}
