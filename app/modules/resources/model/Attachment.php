<?php
App::uses("AppModel", "Model");
App::uses("AttachmentUtility", "modules/resources/utility");

/**
 * Entity Attachment
 * 
 * @author Giuseppe Sassone
 *
 */
class Attachment extends AppModel {
	public $avoidContent= false; // se true evita di recuperare il content salvato
	public $arrayBelongsTo= array (
			'tpattachment_fk' => array (
					'className' => 'Tpattachment',
					'foreignKey' => 'tpattachment' 
			) 
	);

	public function afterFind($results, $primary= false) {
		AttachmentUtility::setAttachmentMapping($this->alias, $results, $this->avoidContent);
		foreach ( $results as &$obj ) {
			if ($this->avoidContent) {
				$obj [$this->alias] ['content']= null;
			}
		}
		return parent::afterFind($results, $primary);
	}
}
