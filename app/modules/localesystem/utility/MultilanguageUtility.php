<?php
App::uses('I18n', 'I18n');
App::uses("Enables", "Config/system");
App::uses("ArrayUtility", "modules/coreutils/utility");
App::uses("LogUtility", "modules/coreutils/utility");
App::uses("Multilanguage", "Model");

class MultilanguageUtility {

	/**
	 * Metodo richiamabile in afterFind delle entity per tradurre dei campi multilingua dalla tabella di db multilanguages
	 * @param type[] $data risultati di una query cake
	 * @param string $table nome della tabella dell'entity proprietaria dei campi
	 * @param string $class nome della classe dell'entity proprietaria dei campi
	 * @param array $multilanguages lista dei campi multilingua da tradurre nella lingua di sessione
	 * @param string $language indica in quale lingua tradurre i campi. Se non definito sceglie la lingua in sessione (di default null)
	 */
	static function setFieldLanguage(&$data, $table, $class, $multilanguages= array(), $language= null) {
		if (empty($language)) {
			$language= CakeSession::read('Config.language');
		}
		if (! ArrayUtility::isEmpty($multilanguages) && ! empty($language)) {
			foreach ( $data as &$obj ) {
				if (array_key_exists($class, $obj)) {
					$multilanguage= new Multilanguage();
					foreach ( $multilanguages as $field ) {
						if (array_key_exists($field, $obj [$class])) {
							$val= $multilanguage->find('first', array (
									'conditions' => array (
											'tablename' => $table,
											'fieldname' => $field,
											'languagecod' => $language,
											'objraw' => $obj [$class] ['id'] 
									),
									'fields' => array (
											'content' 
									) 
							));
							if (! empty($val)) {
								$obj [$class] [$field]= $val ['Multilanguage'] ['content'];
							}
						}
					}
				}
			}
		}
	}
}