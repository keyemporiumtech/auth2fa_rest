<?php
App::uses('AppController', 'Controller');
App::uses("Defaults", "Config/system");
App::uses("LocaleUtility", "modules/localesystem/utility");
App::uses("TranslatorUtility", "modules/cakeutils/utility");

class LocalesystemController extends AppController {

    public function home() {
        $this->set("language", CakeSession::read('Config.language'));
    }

    public function change() {
        CakeSession::write('Config.language', 'eng');
        $this->redirect(array(
            'action' => 'home',
        ));
    }

    public function reset() {
        CakeSession::write('Config.language', Defaults::get('language'));
        $this->redirect(array(
            'action' => 'home',
        ));
    }

    public function translatorutility() {
        $this->set("testo", TranslatorUtility::__translate("TEST", "test"));
        $this->set("testoParam", TranslatorUtility::__translate_args("TEST_PARAM", array(
            "key",
        ), "test"));
    }

    public function localeutility() {
        $language = CakeSession::read('Config.language');
        $this->set("language", LocaleUtility::getLanguage($language));
        $this->set("languageSpecific", LocaleUtility::getLanguageSpecific($language));
    }

    public function translations($grouped = false) {
        parent::evalParam($grouped, 'grouped');

        $language = CakeSession::read('Config.language');
        $this->set("language", $language);
        $this->set("languageLocal", LocaleUtility::getLanguage($language));
        $this->set("languageSpecific", LocaleUtility::getLanguageSpecific($language));

        $languages = TranslatorUtility::getAvailableLanguages();
        $this->set("languages", $languages);

        $pos = array();
        $groups = array();
        if (!$grouped) {
            $pos = TranslatorUtility::getAllPoFields();
        } else {
            $groups = TranslatorUtility::getAllPoFieldsGrouped();
        }
        $this->set("pos", $pos);
        $this->set("groups", $groups);
        $this->set("grouped", $grouped);
    }

    public function changeTranslation($lan = null, $grouped = false) {
        parent::evalParam($lan, 'lan');
        parent::evalParam($grouped, 'grouped');
        CakeSession::write('Config.language', $lan);
        $this->redirect(array(
            'action' => 'translations',
            '?' => array('grouped' => $grouped),
        ));
    }
}
