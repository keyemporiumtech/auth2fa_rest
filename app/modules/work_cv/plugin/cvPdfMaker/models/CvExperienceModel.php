<?php
App::uses("TranslatorUtility", "modules/cakeutils/utility");
App::uses('CvPdfConstant', 'modules/work_cv/plugin/cvPdfMaker/config');

class CvExperienceModel {
    public $dtainit;
    public $dtaend;
    public $period = "";
    public $companies = array();
    public $sector;
    public $place;
    public $roles = array(); // CvRoleModel
    public $skill_knw = array();
    public $skill_prd = array();

    function __construct($dtainit = null, $dtaend = null, $period = "") {
        $this->dtainit = $dtainit;
        $this->dtaend = $dtaend;
        $this->period = $period;
        if (!empty($this->dtainit)) {
            $this->period = $this->dtainit . " - " . (empty($this->dtaend) ? TranslatorUtility::__translate("CV_PERIOD_ONGOING", CvPdfConstant::$TRANSLATOR_FILE) : $this->dtaend);
        }
    }
}