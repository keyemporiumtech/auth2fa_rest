<?php
App::uses("FileUtility", "modules/coreutils/utility");
App::uses("DateUtility", "modules/coreutils/utility");
App::uses('PdfStructureModel', 'modules/util_pdf/classes');
App::uses('ProfessionDto', 'modules/work_cv/classes');
App::uses("CvPdfMaker", "modules/work_cv/plugin/cvPdfMaker");
App::uses('CvPdfConstant', 'modules/work_cv/plugin/cvPdfMaker/config');
// plugin
App::uses('CvPdf', 'modules/work_cv/plugin/cvPdfMaker');
App::uses('CvInfoModel', 'modules/work_cv/plugin/cvPdfMaker/models');
App::uses('CvIntestModel', 'modules/work_cv/plugin/cvPdfMaker/models');
App::uses('CvExperienceModel', 'modules/work_cv/plugin/cvPdfMaker/models');
App::uses('CvRoleModel', 'modules/work_cv/plugin/cvPdfMaker/models');
App::uses('CvSchoolModel', 'modules/work_cv/plugin/cvPdfMaker/models');
App::uses('CvSkillModel', 'modules/work_cv/plugin/cvPdfMaker/models');
// inner
App::uses("ProfessionBS", "modules/work_cv/business");
App::uses("UserBS", "modules/authentication/business");
App::uses("ActivityBS", "modules/authentication/business");
App::uses("AttachmentBS", "modules/resources/business");
App::uses("AddressBS", "modules/localesystem/business");
App::uses("AddressUtility", "modules/localesystem/utility");
App::uses("ContactreferenceBS", "modules/authentication/business");
App::uses("UseraddressBS", "modules/authentication/business");
App::uses("NationBS", "modules/localesystem/business");
App::uses("ProfessionexperienceBS", "modules/work_cv/business");
App::uses("WorkroleBS", "modules/work_company/business");
App::uses("EnumSkillType", "modules/work_company/config");
App::uses("WorkexperiencecompanyBS", "modules/work_company/business");
App::uses("WorkexperienceroleBS", "modules/work_company/business");
App::uses("WorkexperienceskillBS", "modules/work_company/business");
App::uses("ProfessionschoolBS", "modules/work_cv/business");
App::uses("ProfessionskillBS", "modules/work_cv/business");
App::uses("ProfessionroleBS", "modules/work_cv/business");

class ProfessionUtility {

    static function pdf($id = null, $cod = null, $webroot = "", $name = null, PdfStructureModel $structure = null) {
        $profession = ProfessionUtility::profession($id, $cod, CvPdfConstant::$MAX_CHARS_LN);
        $plugin = null;
        if (!empty($profession)) {
            if (empty($name)) {
                $name = "CV_" . str_replace(" ", "_", $profession->intest->name);
            }
            $plugin = CvPdfMaker::makePlugin($webroot, $name, $structure, $profession->intest, $profession->info, $profession->roles, $profession->experiences, $profession->schools, $profession->skills_lan, $profession->skills_knw, $profession->skills_prd);
        }
        return $plugin;
    }

    static function clean($id = null, $cod = null) {
        if (empty($id) && empty($cod)) {
            return false;
        }
        // profession
        $professionBS = new ProfessionBS();
        if (!empty($cod)) {
            $professionBS->addCondition("cod", $cod);
        }
        $profession = $professionBS->unique($id);
        if (empty($profession)) {
            return false;
        }
        if (!empty($profession['Profession']['image'])) {
            $attachmentBS = new AttachmentBS();
            $attachment = $attachmentBS->unique($profession['Profession']['image']);
            $name_image = "CV_PHOTO_" . $attachment['Attachment']['id'] . "." . $attachment['Attachment']['ext'];
            $url_image = WWW_ROOT . "tmp/{$name_image}";
            return unlink($url_image);
        }
        return false;
    }

    static function profession($id = null, $cod = null, $descriptionLn = 0) {
        if (empty($id) && empty($cod)) {
            return null;
        }

        $obj = new ProfessionDto();

        // profession
        $professionBS = new ProfessionBS();
        $professionBS->acceptNull = true;
        if (!empty($cod)) {
            $professionBS->addCondition("cod", $cod);
        }
        $profession = $professionBS->unique($id);
        if (empty($profession)) {
            return null;
        }

        $obj->name = $profession['Profession']['name'];
        $obj->cod = $profession['Profession']['cod'];

        // intest
        $userBS = new UserBS();
        $userBS->addVirtualField("completename");
        $user = $userBS->unique($profession['Profession']['user']);

        $url_image = "";
        if (!empty($profession['Profession']['image'])) {
            $attachmentBS = new AttachmentBS();
            $attachment = $attachmentBS->unique($profession['Profession']['image']);
            $name_image = "CV_PHOTO_" . $attachment['Attachment']['id'] . "." . $attachment['Attachment']['ext'];
            $url_image = "tmp/{$name_image}";
            FileUtility::putFileToTemporaryFolder($name_image, base64_decode($attachment['Attachment']['content']));
        }

        $intest = new CvIntestModel();
        $intest->name = $user['User']['completename'];
        $intest->image_url = $url_image;

        $obj->intest = $intest;

        // info
        $addressVal = "";
        if (!empty($profession['Profession']['address'])) {
            $address = AddressUtility::getAddressString($profession['Profession']['address']);
            $addressVal = !empty($address) ? $address : "";
        }
        $phoneVal = "";
        if (!empty($profession['Profession']['phone'])) {
            $phoneBS = new ContactreferenceBS();
            $phone = $phoneBS->unique($profession['Profession']['phone']);
            $phoneVal = !empty($phone) ? $phone['Contactreference']['prefix'] . $phone['Contactreference']['val'] : "";
        }
        $emailVal = "";
        if (!empty($profession['Profession']['email'])) {
            $emailBS = new ContactreferenceBS();
            $email = $emailBS->unique($profession['Profession']['email']);
            $emailVal = !empty($email) ? $email['Contactreference']['val'] : "";
        }

        $useraddressBS = new UseraddressBS();
        $useraddressBS->addBelongsTo("address_fk");
        $useraddressBS->addCondition("user", $profession['Profession']['user']);
        $useraddressBS->addCondition("flgprincipal", 1);
        $useraddressBS->acceptNull = true;
        $useraddress = $useraddressBS->unique();
        $nationalityVal = "";
        if (!empty($useraddress)) {
            $residence = $useraddress['Useraddress']['address_fk'];
            $nationalityBS = new NationBS();
            $nationality = $nationalityBS->unique($residence['nation']);
            $nationalityVal = !empty($nationality) ? $nationality['Nation']['name'] : "";
        }

        $info = new CvInfoModel();
        $info->name = $intest->name;
        $info->address = $addressVal;
        $info->phone = $phoneVal;
        $info->email = $emailVal;
        $info->born = DateUtility::getDateFormat('d/m/Y', $user['User']['born']);
        $info->nation = $nationalityVal;

        $obj->info = $info;

        // experiences
        ProfessionUtility::putExperiences($profession['Profession']['id'], $obj, $descriptionLn);

        // schools
        ProfessionUtility::putSchools($profession['Profession']['id'], $obj, $descriptionLn);

        // skills
        ProfessionUtility::putSkills($profession['Profession']['id'], $obj, $descriptionLn);

        // roles
        ProfessionUtility::putRoles($profession['Profession']['id'], $obj, $descriptionLn);

        return $obj;
    }

    static function putExperiences($id_profession, ProfessionDto &$dto, $descriptionLn = 0) {
        $professionexperiencesBS = new ProfessionexperienceBS();
        $professionexperiencesBS->addBelongsTo("workexperience_fk");
        $professionexperiencesBS->addCondition("profession", $id_profession);
        $professionexperiencesBS->acceptNull = true;
        $professionexperiences = $professionexperiencesBS->all();

        $experiences = array();
        $experience = null;
        foreach ($professionexperiences as $professionexperience) {
            $experienceModel = $professionexperience['Professionexperience']['workexperience_fk'];
            $experience = new CvExperienceModel(DateUtility::getDateFormat('d/m/Y', $experienceModel['dtainit']), DateUtility::getDateFormat('d/m/Y', $experienceModel['dtaend']));

            $companyBS = new ActivityBS();
            $companyBS->addBelongsTo("tpcat_fk");
            $company = $companyBS->unique($experienceModel['company']);
            array_push($experience->companies, $company['Activity']['namecod']);
            $sector = $company['Activity']['tpcat_fk'];
            $experience->sector = $sector['title'];
            $experience->place = $experienceModel['place'];

            $workroleBS = new WorkroleBS();
            $workrole = $workroleBS->unique($experienceModel['role']);
            $role = new CvRoleModel($workrole['Workrole']['name'], $workrole['Workrole']['description'], $descriptionLn);
            array_push($experience->roles, $role);

            // companies
            $workexperiencecompanyBS = new WorkexperiencecompanyBS();
            $workexperiencecompanyBS->addBelongsTo("activity_fk");
            $workexperiencecompanyBS->addCondition("experience", $experienceModel['id']);
            $workexperiencecompanies = $workexperiencecompanyBS->all();

            foreach ($workexperiencecompanies as $workexperiencecompany) {
                $companyModel = $workexperiencecompany['Workexperiencecompany']['activity_fk'];
                array_push($experience->companies, $companyModel['namecod']);
            }

            // roles
            $workexperienceroleBS = new WorkexperienceroleBS();
            $workexperienceroleBS->addBelongsTo("workrole_fk");
            $workexperienceroleBS->addCondition("experience", $experienceModel['id']);
            $workexperienceroles = $workexperienceroleBS->all();

            foreach ($workexperienceroles as $workexperiencerole) {
                $roleModel = $workexperiencerole['Workexperiencerole']['workrole_fk'];
                $role = new CvRoleModel($roleModel['name'], $roleModel['description'], $descriptionLn);
                array_push($experience->roles, $role);
            }

            // skillKnw
            $workexperienceskillBS = new WorkexperienceskillBS();
            $workexperienceskillBS->addBelongsTo("workskill_fk");
            $workexperienceskillBS->addCondition("workskill_fk.tpskill", EnumSkillType::KNOWLEDGMENTS);
            $workexperienceskillBS->addCondition("experience", $experienceModel['id']);
            $workexperienceskills = $workexperienceskillBS->all();

            foreach ($workexperienceskills as $workexperienceskill) {
                $skillModel = $workexperienceskill['Workexperienceskill']['workskill_fk'];
                $skill = new CvSkillModel($skillModel['name'], $workexperienceskill['Workexperienceskill']['levelval'], $skillModel['levelmax'], $workexperienceskill['Workexperienceskill']['gg'], $skillModel['description'], $descriptionLn);
                array_push($experience->skill_knw, $skill);
            }

            // skillPrd
            $workexperienceskillBS = new WorkexperienceskillBS();
            $workexperienceskillBS->addBelongsTo("workskill_fk");
            $workexperienceskillBS->addCondition("workskill_fk.tpskill", EnumSkillType::PRODUCTS_INSTRUMENTS);
            $workexperienceskillBS->addCondition("experience", $experienceModel['id']);
            $workexperienceskills = $workexperienceskillBS->all();

            foreach ($workexperienceskills as $workexperienceskill) {
                $skillModel = $workexperienceskill['Workexperienceskill']['workskill_fk'];
                $skill = new CvSkillModel($skillModel['name'], $workexperienceskill['Workexperienceskill']['levelval'], $skillModel['levelmax'], $workexperienceskill['Workexperienceskill']['gg'], $skillModel['description'], $descriptionLn);
                array_push($experience->skill_prd, $skill);
            }

            array_push($experiences, $experience);
        }

        $dto->experiences = $experiences;
    }

    static function putSchools($id_profession, ProfessionDto &$dto, $descriptionLn = 0) {
        $professionschoolBS = new ProfessionschoolBS();
        $professionschoolBS->addBelongsTo("activity_fk");
        $professionschoolBS->addCondition("profession", $id_profession);
        $professionschools = $professionschoolBS->all();

        $scholls = array();
        foreach ($professionschools as $professionschool) {
            $school = new CvSchoolModel($professionschool['Professionschool']['name'], $professionschool['Professionschool']['levelval'], $professionschool['Professionschool']['levelmax'], $professionschool['Professionschool']['description'], $descriptionLn);
            $school->date = DateUtility::getDateFormat('d/m/Y', $professionschool['Professionschool']['dtainit']) . (!empty($professionschool['Professionschool']['dtaend']) ? " - " . DateUtility::getDateFormat('d/m/Y', $professionschool['Professionschool']['dtaend']) : "");
            $institute = $professionschool['activity_fk'];
            $school->institute = $institute['namecod'];
            array_push($scholls, $school);
        }

        $dto->schools = $scholls;
    }

    static function putSkills($id_profession, ProfessionDto &$dto, $descriptionLn = 0) {
        $professionskillBS = new ProfessionskillBS();
        $professionskillBS->addBelongsTo("workskill_fk");
        $professionskillBS->addCondition("workskill_fk.tpskill", EnumSkillType::LANGUAGES);
        $professionskillBS->addCondition("profession", $id_profession);
        $professionskills = $professionskillBS->all();

        $skills_lan = array();
        foreach ($professionskills as $professionskill) {
            $skillModel = $professionskill['Professionskill']['workskill_fk'];
            $skill = new CvSkillModel($skillModel['name'], $professionskill['Professionskill']['levelval'], $skillModel['levelmax'], $professionskill['Professionskill']['gg'], $skillModel['description'], $descriptionLn);
            array_push($skills_lan, $skill);
        }

        $dto->skills_lan = $skills_lan;

        $professionskillBS = new ProfessionskillBS();
        $professionskillBS->addBelongsTo("workskill_fk");
        $professionskillBS->addCondition("workskill_fk.tpskill", EnumSkillType::KNOWLEDGMENTS);
        $professionskillBS->addCondition("profession", $id_profession);
        $professionskills = $professionskillBS->all();

        $skills_knw = array();
        foreach ($professionskills as $professionskill) {
            $skillModel = $professionskill['Professionskill']['workskill_fk'];
            $skill = new CvSkillModel($skillModel['name'], $professionskill['Professionskill']['levelval'], $skillModel['levelmax'], $professionskill['Professionskill']['gg'], $skillModel['description'], $descriptionLn);
            array_push($skills_knw, $skill);
        }

        $dto->skills_knw = $skills_knw;

        $professionskillBS = new ProfessionskillBS();
        $professionskillBS->addBelongsTo("workskill_fk");
        $professionskillBS->addCondition("workskill_fk.tpskill", EnumSkillType::PRODUCTS_INSTRUMENTS);
        $professionskillBS->addCondition("profession", $id_profession);
        $professionskills = $professionskillBS->all();

        $skills_prd = array();
        foreach ($professionskills as $professionskill) {
            $skillModel = $professionskill['Professionskill']['workskill_fk'];
            $skill = new CvSkillModel($skillModel['name'], $professionskill['Professionskill']['levelval'], $skillModel['levelmax'], $professionskill['Professionskill']['gg'], $skillModel['description'], $descriptionLn);
            array_push($skills_prd, $skill);
        }

        $dto->skills_prd = $skills_prd;
    }

    static function putRoles($id_profession, ProfessionDto &$dto, $descriptionLn = 0) {
        $professionroleBS = new ProfessionroleBS();
        $professionroleBS->addBelongsTo("workrole_fk");
        $professionroleBS->addCondition("profession", $id_profession);
        $professionroles = $professionroleBS->all();

        $roles = array();
        foreach ($professionroles as $professionrole) {
            $roleModel = $professionrole['Professionrole']['workrole_fk'];
            $role = new CvRoleModel($roleModel['name'], $roleModel['description'], $descriptionLn);
            array_push($roles, $role);
        }

        $dto->roles = $roles;

    }
}
