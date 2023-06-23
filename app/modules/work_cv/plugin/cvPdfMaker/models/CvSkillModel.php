<?php
App::uses("TranslatorUtility", "modules/cakeutils/utility");
App::uses('CvPdfConstant', 'modules/work_cv/plugin/cvPdfMaker/config');
App::uses('PdfUtility', 'modules/util_pdf/utility');
class CvSkillModel {
    public $name;
    public $gg;
    public $period = "";
    public $description;
    public $descriptions = array();
    public $level;
    public $levelmax;
    public $vote = "";

    function __construct($name, $level, $levelmax, $gg, $description = null, $length = 0) {
        $this->name = $name;
        $this->level = $level;
        $this->levelmax = $levelmax;
        $this->description = $description;
        $this->gg = $gg;
        if (!empty($this->gg) && is_numeric($this->gg)) {
            if ($this->gg == 1) {
                $this->period = $this->gg . " " . TranslatorUtility::__translate("CV_PERIOD_DAY", CvPdfConstant::$TRANSLATOR_FILE);
            } elseif ($this->gg > 30 && $this->gg < 360) {
                $num_months = floor($this->gg / 30);
                $remains = $this->gg - ($num_months * 30);
                $this->period = $num_months . " " . TranslatorUtility::__translate($num_months == 1 ? "CV_PERIOD_MONTH" : "CV_PERIOD_MONTHS", CvPdfConstant::$TRANSLATOR_FILE);
                if ($remains > 0) {
                    $this->period .= ", " . $remains . " " . TranslatorUtility::__translate($remains == 1 ? "CV_PERIOD_DAY" : "CV_PERIOD_DAYS", CvPdfConstant::$TRANSLATOR_FILE);
                }
            } elseif ($this->gg > 360) {
                $num_years = floor($this->gg / 360);
                $remains = $this->gg - ($num_years * 360);
                $this->period = $num_years . " " . TranslatorUtility::__translate($num_years == 1 ? "CV_PERIOD_YEAR" : "CV_PERIOD_YEARS", CvPdfConstant::$TRANSLATOR_FILE);
                if ($remains > 0) {
                    if ($remains < 30) {
                        $this->period .= ", " . $remains . " " . TranslatorUtility::__translate($remains == 1 ? "CV_PERIOD_DAY" : "CV_PERIOD_DAYS", CvPdfConstant::$TRANSLATOR_FILE);
                    } else {
                        $num_months = floor($remains / 30);
                        $remains2 = $remains - ($num_months * 30);
                        $this->period .= ", " . $num_months . " " . TranslatorUtility::__translate($num_months == 1 ? "CV_PERIOD_MONTH" : "CV_PERIOD_MONTHS", CvPdfConstant::$TRANSLATOR_FILE);
                        if ($remains2 > 0) {
                            $this->period .= ", " . $remains2 . " " . TranslatorUtility::__translate($remains2 == 1 ? "CV_PERIOD_DAY" : "CV_PERIOD_DAYS", CvPdfConstant::$TRANSLATOR_FILE);
                        }
                    }
                }
            }
        }
        if (!empty($description)) {
            $this->descriptions = PdfUtility::splitText($description, $length);
        }
        if (!empty($this->level) && !empty($this->levelmax)) {
            $this->vote = $this->level . "/" . $this->levelmax;
        } else if (!empty($this->level) && empty($this->levelmax)) {
            $this->vote = $this->level;
        }

    }
}