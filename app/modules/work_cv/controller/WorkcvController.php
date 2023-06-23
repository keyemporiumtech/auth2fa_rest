<?php
App::uses('AppController', 'Controller');
// plugin
App::uses('CvGenericModel', 'modules/work_cv/plugin/cvPdfMaker/models');
App::uses('CvInfoModel', 'modules/work_cv/plugin/cvPdfMaker/models');
App::uses('CvExperienceModel', 'modules/work_cv/plugin/cvPdfMaker/models');
App::uses('CvRoleModel', 'modules/work_cv/plugin/cvPdfMaker/models');
App::uses('CvSchoolModel', 'modules/work_cv/plugin/cvPdfMaker/models');
App::uses('CvSkillModel', 'modules/work_cv/plugin/cvPdfMaker/models');
App::uses("CvPdfMaker", "modules/work_cv/plugin/cvPdfMaker");
App::uses('ProfessionUtility', 'modules/work_cv/utility');

class WorkcvController extends AppController {

    public function home() {
    }

    public function createPdf() {
        // CvPdfMaker::example();
        $generic = new CvGenericModel();

        $info = new CvInfoModel();
        $info->name = "Giuseppe Sassone";
        $info->image_url = "img/logo.png";
        $info->address = "Via Roma, 72 - 00153 - Roma RM";
        $info->phone = "+393338765432";
        $info->email = "giuseppe@email.it";
        $info->nation = "Italia";
        $info->born = "25/05/1980";

        $info->roles = array();

        $info->experiences = array($this->makeExperience(null), $this->makeExperience(), $this->makeExperience());

        $info->schools = array($this->makeSchool(), $this->makeSchool(), $this->makeSchool());

        $info->skill_lan = array($this->makeSkill("Italiano", 90, "Buono"), $this->makeSkill("Inglese", 590, "Ottimo"), $this->makeSkill("Francese", 780, "Madrelingua"));
        $info->skill_knw = array($this->makeSkill("Suonare", 90, 60, 60), $this->makeSkill("Scrivere", 590, 54, 60), $this->makeSkill("Leggere", 780, "Buona competenza"));
        $info->skill_prd = array($this->makeSkill("Pianoforte", 90, "Buono"), $this->makeSkill("PC", 590, 9, 10));

        CvPdfMaker::printCV("", null, null, $generic, $info);
    }

    public function cvProfession() {
        $plugin = ProfessionUtility::pdf(null, "CV_GIUSEPPE_SASSONE_INF");
        $plugin->preview();
    }

    private function makeExperience($dtaend = "12/12/2000") {
        $experience = new CvExperienceModel("12/10/2000", $dtaend);
        array_push($experience->companies, "Azienda Primaria", "Azienda Secondaria");
        $experience->sector = "SETTORE PRIMARIO";
        $experience->place = "Roma Giardinetti - ufficio 1";
        $role1 = $this->makeRole("Addetto alla vendita degli orologi<br/>Addetto al magazzino\nAddetto alla fornitura");
        $role2 = $this->makeRole("Addetto alla vendita degli orologi Addetto al magazzino Addetto alla fornitura Addetto alla scrittura lunga di questa descrizione");
        array_push($experience->roles, $role1, $role2);
        array_push($experience->skill_knw, $this->makeSkill("Competenza 1"), $this->makeSkill("Competenza 2"));
        array_push($experience->skill_prd, $this->makeSkill("Prodotto 1"), $this->makeSkill("Prodotto 2"));
        return $experience;
    }

    private function makeRole($description = "Ruolo importante") {
        $role = new CvRoleModel("RUOLO Principale", $description, 61);
        return $role;
    }

    private function makeSchool($description = "Ruolo importante") {
        $school = new CvSchoolModel("Laurea Principale", 90, 110, $description, 61);
        $school->date = "2000";
        $school->institute = "Università Roma 3";
        return $school;
    }

    private function makeSkill($name, $gg = 90, $level = null, $levelMax = null) {
        $skill = new CvSkillModel($name, $level, $levelMax, $gg, "", 61);
        return $skill;
    }
}