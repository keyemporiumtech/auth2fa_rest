<?php
class CvInfoModel {
    public $name;
    public $image_url; // from webroot
    public $image_width = 0; // px
    public $image_height = 280; // px
    public $address;
    public $phone;
    public $email;
    public $nation;
    public $born;
    public $roles = array();
    public $experiences = array();
    public $schools = array();
    public $skill_lan = array();
    public $skill_knw = array();
    public $skill_prd = array();

    function references() {
        return (!empty($this->phone) ? "[{$this->phone}]" : "") . (!empty($this->email) ? " [{$this->email}]" : "");
    }
}