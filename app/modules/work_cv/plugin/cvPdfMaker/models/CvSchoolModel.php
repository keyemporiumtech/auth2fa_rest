<?php
App::uses('PdfUtility', 'modules/util_pdf/utility');
class CvSchoolModel {
    public $date;
    public $institute;
    public $name;
    public $description;
    public $descriptions = array();
    public $level;
    public $levelmax;
    public $vote = "";

    function __construct($name, $level, $levelmax, $description = null, $length = 0) {
        $this->name = $name;
        $this->level = $level;
        $this->levelmax = $levelmax;
        $this->description = $description;
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