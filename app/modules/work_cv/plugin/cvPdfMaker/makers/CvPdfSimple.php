<?php
// plugin
App::uses('CvPdf', 'modules/work_cv/plugin/cvPdfMaker');
App::uses('CvInfoModel', 'modules/work_cv/plugin/cvPdfMaker/models');
// models
App::uses('CvExperienceModel', 'modules/work_cv/plugin/cvPdfMaker/models');
App::uses('CvRoleModel', 'modules/work_cv/plugin/cvPdfMaker/models');
App::uses('CvSchoolModel', 'modules/work_cv/plugin/cvPdfMaker/models');
App::uses('CvSkillModel', 'modules/work_cv/plugin/cvPdfMaker/models');

class CvPdfSimple {

    /**
     * PARENT
     *
     * @var CvPdf
     */
    public $PARENT;

    function __construct(CvPdf $parent) {
        $this->PARENT = $parent;
    }

    function makeCv(CvInfoModel $info) {
        $this->makeIntest($info);
        $this->makeInfo($info);
        $this->makeRoles($info->roles);
        $this->makeExperiences($info->experiences);
        $this->makeSchools($info->schools);
        $this->makeSkills($info->skill_lan, $info->skill_knw, $info->skill_prd);
    }

    // OTHERS
    function makeIntest(CvInfoModel $info) {
        $this->PARENT->pdf->text($this->PARENT->generic->color, "16", $this->PARENT->font, "B");
        $this->PARENT->write($this->PARENT->translate("CV_TITLE"), $this->PARENT->space, 5, 60, 5, "L", $this->PARENT->border);
        $this->PARENT->write($info->name, 10, 5, 60, 5, "L", $this->PARENT->border);
        if (!empty($info->image_url)) {
            $this->PARENT->addCleanFile($info->image_url);
            $this->PARENT->pdf->writeImage($info->image_url, 65, 10, $info->image_width, $info->image_height, true);
        }

        $this->PARENT->pdf->space($this->PARENT->space);
    }

    function makeInfo(CvInfoModel $info, $top = 65) {
        $this->PARENT->pdf->text($this->PARENT->generic->color, "14", $this->PARENT->font, "B");
        $this->PARENT->write($this->PARENT->translate("CV_INFO"), $top, 5, 60, 5, "L", $this->PARENT->border);

        $this->PARENT->pdf->space($this->PARENT->space);

        $this->PARENT->pdf->text($this->PARENT->generic->color, "11", $this->PARENT->font);
        $this->PARENT->write($this->PARENT->translate("CV_INFO_NAME"), $this->PARENT->space, 5, 60, 5, "R", $this->PARENT->border);
        $this->PARENT->pdf->text($this->PARENT->generic->color, "11", $this->PARENT->font, "B");
        $this->PARENT->write($info->name, 0, 75, 120, 5, "L", $this->PARENT->border);

        $this->PARENT->pdf->text($this->PARENT->generic->color, "11", $this->PARENT->font);
        $this->PARENT->write($this->PARENT->translate("CV_INFO_ADDRESS"), $this->PARENT->space, 5, 60, 5, "R", $this->PARENT->border);
        $this->PARENT->pdf->text($this->PARENT->generic->color, "11", $this->PARENT->font, "B");
        $this->PARENT->write($info->address, 0, 75, 120, 5, "L", $this->PARENT->border);

        $this->PARENT->pdf->text($this->PARENT->generic->color, "11", $this->PARENT->font);
        $this->PARENT->write($this->PARENT->translate("CV_INFO_PHONE"), $this->PARENT->space, 5, 60, 5, "R", $this->PARENT->border);
        $this->PARENT->pdf->text($this->PARENT->generic->color, "11", $this->PARENT->font, "B");
        $this->PARENT->write($info->phone, 0, 75, 120, 5, "L", $this->PARENT->border);

        $this->PARENT->pdf->text($this->PARENT->generic->color, "11", $this->PARENT->font);
        $this->PARENT->write($this->PARENT->translate("CV_INFO_EMAIL"), $this->PARENT->space, 5, 60, 5, "R", $this->PARENT->border);
        $this->PARENT->pdf->text($this->PARENT->generic->color, "11", $this->PARENT->font, "B");
        $this->PARENT->write($info->email, 0, 75, 120, 5, "L", $this->PARENT->border);

        $this->PARENT->pdf->text($this->PARENT->generic->color, "11", $this->PARENT->font);
        $this->PARENT->write($this->PARENT->translate("CV_INFO_NATION"), $this->PARENT->space, 5, 60, 5, "R", $this->PARENT->border);
        $this->PARENT->pdf->text($this->PARENT->generic->color, "11", $this->PARENT->font, "B");
        $this->PARENT->write($info->nation, 0, 75, 120, 5, "L", $this->PARENT->border);

        $this->PARENT->pdf->text($this->PARENT->generic->color, "11", $this->PARENT->font);
        $this->PARENT->write($this->PARENT->translate("CV_INFO_BORN"), $this->PARENT->space, 5, 60, 5, "R", $this->PARENT->border);
        $this->PARENT->pdf->text($this->PARENT->generic->color, "11", $this->PARENT->font, "B");
        $this->PARENT->write($info->born, 0, 75, 120, 5, "L", $this->PARENT->border);

        $this->PARENT->pdf->space($this->PARENT->space);
    }

    // ---------- ROLES
    function makeRoles($roles = array(), $top = 15) {
        $this->PARENT->pdf->text($this->PARENT->generic->color, "14", $this->PARENT->font, "B");
        $this->PARENT->write($this->PARENT->translate("CV_ROLES"), $top, 5, 60, 5, "L", $this->PARENT->border);

        $this->PARENT->pdf->space($this->PARENT->space);

        $i = 0;
        foreach ($roles as $role) {
            $this->PARENT->write($role->name, ($i > 0 ? 5 : 0), 75, 120, 5, "L", $this->PARENT->border);
            $i++;
        }

    }

    // ---------- EXPERIENCES
    function makeExperiences($experiences = array(), $top = 15) {
        $this->PARENT->pdf->text($this->PARENT->generic->color, "14", $this->PARENT->font, "B");
        $this->PARENT->write($this->PARENT->translate("CV_EXPERIENCE"), $top, 5, 60, 5, "L", $this->PARENT->border);

        $this->PARENT->pdf->space($this->PARENT->space);

        foreach ($experiences as $experience) {
            $this->makeExperience($experience);
        }

    }

    function makeExperience(CvExperienceModel $experience) {

        $this->PARENT->pdf->text($this->PARENT->generic->color, "11", $this->PARENT->font);
        $this->PARENT->write($this->PARENT->translate("CV_EXPERIENCE_PERIOD"), $this->PARENT->space, 5, 60, 5, "R", $this->PARENT->border);
        $this->PARENT->pdf->text($this->PARENT->generic->color, "11", $this->PARENT->font, "B");
        $this->PARENT->write($experience->period, 0, 75, 120, 5, "L", $this->PARENT->border);

        $this->PARENT->pdf->text($this->PARENT->generic->color, "11", $this->PARENT->font);
        $this->PARENT->write($this->PARENT->translate("CV_EXPERIENCE_COMPANY"), $this->PARENT->space, 5, 60, 5, "R", $this->PARENT->border);
        $i = 0;
        foreach ($experience->companies as $company) {
            $this->PARENT->write($company, ($i > 0 ? 5 : 0), 75, 120, 5, "L", $this->PARENT->border);
            $i++;
        }
        $this->PARENT->pdf->space(2);

        $this->PARENT->write($this->PARENT->translate("CV_EXPERIENCE_SECTOR"), $this->PARENT->space, 5, 60, 5, "R", $this->PARENT->border);
        $this->PARENT->write($experience->sector, 0, 75, 120, 5, "L", $this->PARENT->border);
        $this->PARENT->pdf->space(2);

        $this->PARENT->write($this->PARENT->translate("CV_EXPERIENCE_PLACE"), $this->PARENT->space, 5, 60, 5, "R", $this->PARENT->border);
        $this->PARENT->write($experience->place, 0, 75, 120, 5, "L", $this->PARENT->border);
        $this->PARENT->pdf->space(2);

        $this->PARENT->write($this->PARENT->translate("CV_EXPERIENCE_ROLE"), $this->PARENT->space, 5, 60, 5, "R", $this->PARENT->border);
        $i = 0;
        foreach ($experience->roles as $role) {
            $this->makeRole($role, $i);
            $i++;
        }
        $this->PARENT->pdf->space(2);

        $this->PARENT->pdf->text($this->PARENT->generic->color, "11", $this->PARENT->font);
        $this->PARENT->write($this->PARENT->translate("CV_EXPERIENCE_SKILL_KNW"), $this->PARENT->space, 5, 60, 5, "R", $this->PARENT->border);
        $i = 0;
        foreach ($experience->skill_knw as $skill) {
            $this->PARENT->write("- " . $skill->name, ($i > 0 ? 5 : 0), 75, 120, 5, "L", $this->PARENT->border);
            $i++;
        }
        $this->PARENT->pdf->space(2);

        $this->PARENT->pdf->text($this->PARENT->generic->color, "11", $this->PARENT->font);
        $this->PARENT->write($this->PARENT->translate("CV_EXPERIENCE_SKILL_PRD"), $this->PARENT->space, 5, 60, 5, "R", $this->PARENT->border);
        $i = 0;
        foreach ($experience->skill_prd as $skill) {
            $this->PARENT->write("- " . $skill->name, ($i > 0 ? 5 : 0), 75, 120, 5, "L", $this->PARENT->border);
            $i++;
        }
        $this->PARENT->pdf->space(2);

        $this->PARENT->pdf->line(10, $this->PARENT->generic->color, 0.2);
        $this->PARENT->pdf->space($this->PARENT->space);
    }

    function makeRole(CvRoleModel $role, $i = 0) {
        $this->PARENT->pdf->text($this->PARENT->generic->color, "11", $this->PARENT->font, "B");
        $this->PARENT->write($role->name, ($i > 0 ? 5 : 0), 75, 120, 5, "L", $this->PARENT->border);
        $this->PARENT->pdf->text($this->PARENT->generic->color, "11", $this->PARENT->font);
        foreach ($role->descriptions as $description) {
            $this->PARENT->write($description, 5, 85, 120, 5, "L", $this->PARENT->border);
        }
    }

    // ---------- SCHOOL
    function makeSchools($schools = array(), $top = 15) {
        $this->PARENT->pdf->text($this->PARENT->generic->color, "14", $this->PARENT->font, "B");
        $this->PARENT->write($this->PARENT->translate("CV_SCHOOL"), $top, 5, 60, 5, "L", $this->PARENT->border);

        $this->PARENT->pdf->space($this->PARENT->space);

        foreach ($schools as $school) {
            $this->makeSchool($school);
        }

    }

    function makeSchool(CvSchoolModel $school) {

        $this->PARENT->pdf->text($this->PARENT->generic->color, "11", $this->PARENT->font);
        $this->PARENT->write($this->PARENT->translate("CV_SCHOOL_DATE"), $this->PARENT->space, 5, 60, 5, "R", $this->PARENT->border);
        $this->PARENT->pdf->text($this->PARENT->generic->color, "11", $this->PARENT->font, "B");
        $this->PARENT->write($school->date, 0, 75, 120, 5, "L", $this->PARENT->border);

        $this->PARENT->pdf->text($this->PARENT->generic->color, "11", $this->PARENT->font);
        $this->PARENT->write($this->PARENT->translate("CV_SCHOOL_INSTITUTE1"), $this->PARENT->space, 5, 60, 5, "R", $this->PARENT->border);
        $this->PARENT->write($school->institute, 0, 75, 120, 5, "L", $this->PARENT->border);
        $this->PARENT->write($this->PARENT->translate("CV_SCHOOL_INSTITUTE2"), $this->PARENT->space, 5, 60, 5, "R", $this->PARENT->border);
        $this->PARENT->pdf->space(2);

        $this->PARENT->pdf->text($this->PARENT->generic->color, "11", $this->PARENT->font);
        $this->PARENT->write($this->PARENT->translate("CV_SCHOOL_DESCRIPTION"), $this->PARENT->space, 5, 60, 5, "R", $this->PARENT->border);
        $i = 0;
        foreach ($school->descriptions as $description) {
            $this->PARENT->write($description, ($i > 0 ? 5 : 0), 75, 120, 5, "L", $this->PARENT->border);
            $i++;
        }
        $this->PARENT->pdf->space(2);

        $this->PARENT->pdf->text($this->PARENT->generic->color, "11", $this->PARENT->font);
        $this->PARENT->write($this->PARENT->translate("CV_SCHOOL_NAME"), $this->PARENT->space, 5, 60, 5, "R", $this->PARENT->border);
        $this->PARENT->pdf->text($this->PARENT->generic->color, "11", $this->PARENT->font, "B");
        $this->PARENT->write($school->name, 0, 75, 120, 5, "L", $this->PARENT->border);
        $this->PARENT->pdf->space(2);

        $this->PARENT->pdf->text($this->PARENT->generic->color, "11", $this->PARENT->font);
        $this->PARENT->write($this->PARENT->translate("CV_SCHOOL_LEVEL"), $this->PARENT->space, 5, 60, 5, "R", $this->PARENT->border);
        $this->PARENT->write($school->vote, 0, 75, 120, 5, "L", $this->PARENT->border);
        $this->PARENT->pdf->space(2);

        $this->PARENT->pdf->line(10, $this->PARENT->generic->color, 0.2);
        $this->PARENT->pdf->space($this->PARENT->space);
    }

    // ---------- SKILLS
    function makeSkills($skillsLan = array(), $skillsKnw = array(), $skillsPrd = array(), $top = 15) {
        $this->PARENT->pdf->text($this->PARENT->generic->color, "14", $this->PARENT->font, "B");
        $this->PARENT->write($this->PARENT->translate("CV_SKILL"), $top, 5, 60, 5, "L", $this->PARENT->border);

        $this->PARENT->pdf->space($this->PARENT->space);
        $this->makeSkillsLan($skillsLan);
        $this->makeSkillsKnw($skillsKnw);
        $this->makeSkillsPrd($skillsPrd);

    }

    function makeSkillsLan($skills = array(), $top = 10) {
        $this->PARENT->pdf->text($this->PARENT->generic->color, "12", $this->PARENT->font, "B");
        $this->PARENT->write($this->PARENT->translate("CV_SKILL_LAN"), $top, 5, 60, 5, "L", $this->PARENT->border);

        $this->PARENT->pdf->space($this->PARENT->space);

        foreach ($skills as $skill) {
            $this->makeSkill($skill);
            $this->PARENT->pdf->space($this->PARENT->space);
        }
    }

    function makeSkillsKnw($skills = array(), $top = 10) {
        $this->PARENT->pdf->text($this->PARENT->generic->color, "12", $this->PARENT->font, "B");
        $this->PARENT->write($this->PARENT->translate("CV_SKILL_KNW"), $top, 5, 60, 5, "L", $this->PARENT->border);

        $this->PARENT->pdf->space($this->PARENT->space);

        foreach ($skills as $skill) {
            $this->makeSkill($skill);
            $this->PARENT->pdf->space($this->PARENT->space);
        }
    }

    function makeSkillsPrd($skills = array(), $top = 10) {
        $this->PARENT->pdf->text($this->PARENT->generic->color, "12", $this->PARENT->font, "B");
        $this->PARENT->write($this->PARENT->translate("CV_SKILL_PRD"), $top, 5, 60, 5, "L", $this->PARENT->border);

        $this->PARENT->pdf->space($this->PARENT->space);

        foreach ($skills as $skill) {
            $this->makeSkill($skill);
            $this->PARENT->pdf->space($this->PARENT->space);
        }
    }

    function makeSkill(CvSkillModel $skill) {

        $this->PARENT->pdf->text($this->PARENT->generic->color, "11", $this->PARENT->font, "B");
        $this->PARENT->write($skill->name, 0, 75, 120, 5, "L", $this->PARENT->border);

        $this->PARENT->pdf->text($this->PARENT->generic->color, "11", $this->PARENT->font);
        $this->PARENT->write($this->PARENT->translate("CV_SKILL_PERIOD"), $this->PARENT->space, 5, 60, 5, "R", $this->PARENT->border);
        $this->PARENT->write($skill->period, 0, 75, 120, 5, "L", $this->PARENT->border);
        $this->PARENT->write($this->PARENT->translate("CV_SKILL_LEVEL"), $this->PARENT->space, 5, 60, 5, "R", $this->PARENT->border);
        $this->PARENT->write($skill->vote, 0, 75, 120, 5, "L", $this->PARENT->border);
        $this->PARENT->pdf->space($this->PARENT->space);
    }

}