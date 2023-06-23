<?php
App::uses('AppController', 'Controller');
App::uses("TestfkUI", "modules/cakeutils/delegate");
App::uses("TestfkBS", "modules/cakeutils/business");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");
App::uses("FileUtility", "modules/coreutils/utility");
App::uses("CookieUtility", "modules/coreutils/utility");
App::uses("EnumCookieType", "modules/cakeutils/config");
App::uses('EnumQueryLike', 'modules/cakeutils/config');

class CakeutilsController extends AppController {

    public function home() {
        $ui = new TestfkUI();
        $ui->json = true;
        $objJson = '{"id": 1, "name":"prova1"}';
        $objConverted = DelegateUtility::getObj(true, $objJson);
        $this->set("objJson", $objJson);
        $this->set("objConverted", $objConverted);

        $listJson = '[{"id": 1, "name":"prova1"}, {"id": 2, "name":"prova2"}]';
        $listConverted = DelegateUtility::getObjList(true, $listJson);
        $this->set("listJson", $listJson);
        $this->set("listConverted", $listConverted);

        $properties = array(
            new ObjPropertyEntity("cod", null, FileUtility::uuid()),
            new ObjPropertyEntity("title", null, ""),
            new ObjPropertyEntity("result", null, 0),
            new ObjPropertyEntity("test", null, 0),
        );
        $jsonTestfk = '{"id": 1, "cod":"FK003", "title": "HO MODIFICATO LA DESCRIZIONE", "result": 1, "test": 1, "created":"2021-01-05 17:35:14+01:00"}';
        $convertedTestfk = DelegateUtility::mapEntityByJson(new TestfkBS(), $jsonTestfk, $properties);
        $this->set("jsonTestfk", $jsonTestfk);
        $this->set("convertedTestfk", $convertedTestfk);

        $listJsonTestfk = '[{"id": 1, "cod":"FK003", "title": "HO MODIFICATO LA DESCRIZIONE", "result": 1, "test": 1, "created":"2021-01-05 17:35:14+01:00"}, {"cod":"FK003", "title": "NUOVA DESCRIZIONE", "result": 1, "test": 1, "created":"2021-01-05 17:35:14+01:00"}]';
        $listConvertedTestfk = DelegateUtility::mapEntityListByJson(new TestfkBS(), $listJsonTestfk, $properties);
        $this->set("listJsonTestfk", $listJsonTestfk);
        $this->set("listConvertedTestfk", $listConvertedTestfk);

        $convertedTestfkByDelegate = DelegateUtility::mapEntityJsonByDelegate(new TestfkUI(), new TestfkBS(), $jsonTestfk);
        $this->set("convertedTestfkByDelegate", $convertedTestfkByDelegate);

        $listConvertedTestfkByDelegate = DelegateUtility::mapEntityListJsonByDelegate(new TestfkUI(), new TestfkBS(), $listJsonTestfk);
        $this->set("listConvertedTestfkByDelegate", $listConvertedTestfkByDelegate);
    }

    public function appGenericBs() {
        $bs = new TestfkBS();
        $this->set("instance", $bs->instance());

        $bs = new TestfkBS();
        $this->set("unique", $bs->unique(1));

        $bs = new TestfkBS();
        $bs->addBelongsTo("test_fk");
        $this->set("fk", $bs->unique(1));
        $this->set("log", $bs->logDataSource());
        $this->set("logquery", $bs->logDataSource(true));

        $bs = new TestfkBS();
        $listQuery = $bs->genericQuery("SELECT * FROM testfks");
        $this->set("listQuery", $listQuery);

        $bs = new TestfkBS();
        $uniqueQuery = $bs->genericQuery("SELECT * FROM testfks WHERE id = 1");
        $this->set("uniqueQuery", $uniqueQuery);

        $bs = new TestfkBS();
        $listSqlQuery = $bs->query("SELECT * FROM testfks as Testfk");
        $this->set("listSqlQuery", $listSqlQuery);

        $bs = new TestfkBS();
        $uniqueSqlQuery = $bs->query("SELECT * FROM testfks as Testfk WHERE id = 1");
        $this->set("uniqueSqlQuery", $uniqueSqlQuery);
    }

    public function cookieManager() {
        $this->set("isNecessary", CookieUtility::isActiveType(EnumCookieType::NECESSARY));
        $this->set("isPreference", CookieUtility::isActiveType(EnumCookieType::PREFERENCE));
        $this->set("isStatistic", CookieUtility::isActiveType(EnumCookieType::STATISTIC));
        $this->set("isMarketing", CookieUtility::isActiveType(EnumCookieType::MARKETING));
        $this->set("isNotClassified", CookieUtility::isActiveType(EnumCookieType::NOT_CLASSIFIED));

        $this->set("listNecessary", CookieUtility::listType(EnumCookieType::NECESSARY));
        $this->set("listPreference", CookieUtility::listType(EnumCookieType::PREFERENCE));
        $this->set("listStatistic", CookieUtility::listType(EnumCookieType::STATISTIC));
        $this->set("listMarketing", CookieUtility::listType(EnumCookieType::MARKETING));
        $this->set("listNotClassified", CookieUtility::listType(EnumCookieType::NOT_CLASSIFIED));

        $this->set("listInstancedNecessary", CookieUtility::listInstancedType(EnumCookieType::NECESSARY));
        $this->set("listInstancedPreference", CookieUtility::listInstancedType(EnumCookieType::PREFERENCE));
        $this->set("listInstancedStatistic", CookieUtility::listInstancedType(EnumCookieType::STATISTIC));
        $this->set("listInstancedMarketing", CookieUtility::listInstancedType(EnumCookieType::MARKETING));
        $this->set("listInstancedNotClassified", CookieUtility::listInstancedType(EnumCookieType::NOT_CLASSIFIED));

        $this->set("Cookieinner", $this->Cookieinner);
    }

    public function setFlag($type = null, $flag = false) {
        parent::evalParam($type, 'type');
        parent::evalParamBool($flag, 'flag', false);
        CookieUtility::updateType($type, $flag);
        parent::goToPage("cookieManager");
    }

    public function genericGroups() {
        $ui = new TestfkUI();
        $ui->json = false; // true
        $ui->groups = array("GRP1"); // "[\"GRP1\"]"
        $list1 = $ui->table();
        $this->set("list1", $list1);

        $ui = new TestfkUI();
        $ui->json = false; // true
        $ui->groups = array("GRP1", "GRP2"); // "[\"GRP1\",\"GRP2\"]"
        $list2 = $ui->table();
        $this->set("list2", $list2);

        $ui = new TestfkUI();
        $ui->json = false; // true
        $ui->groups = array("GRP"); // "[\"GRP1\"]"
        $ui->likegroups = EnumQueryLike::RIGHT;
        $list3 = $ui->table();
        $this->set("list3", $list3);
    }

    // aggiunta in GRP1 e GRP2
    public function saveInGroup() {

        // GRP1 e GRP2 prima
        $ui = new TestfkUI();
        $ui->json = false; // true
        $ui->groups = array("GRP1"); // "[\"GRP1\"]"
        $listGRP1_PRE = $ui->table();
        $this->set("listGRP1_PRE", $listGRP1_PRE);

        $ui = new TestfkUI();
        $ui->json = false; // true
        $ui->groups = array("GRP2"); // "[\"GRP2\"]"
        $listGRP2_PRE = $ui->table();
        $this->set("listGRP2_PRE", $listGRP2_PRE);

        $bs = new TestfkBS();
        $instance = $bs->instance();
        $instance['Testfk']['title'] = "Prova in gruppi";
        $instance['Testfk']['description'] = "Prova di inserimento entity in gruppi";
        $instance['Testfk']['result'] = 1;
        $instance['Testfk']['test'] = 1;

        $ui = new TestfkUI();
        $ui->json = false; // true
        $ui->groupssave = array("GRP1", "GRP2"); // "[\"GRP1\", \"GRP2\"]"

        $id_save = $ui->save($instance);
        $this->set("id_save", $id_save);

        // GRP1 e GRP2 dopo
        $ui = new TestfkUI();
        $ui->json = false; // true
        $ui->groups = array("GRP1"); // "[\"GRP1\"]"
        $listGRP1_POST = $ui->table();
        $this->set("listGRP1_POST", $listGRP1_POST);

        $ui = new TestfkUI();
        $ui->json = false; // true
        $ui->groups = array("GRP2"); // "[\"GRP2\"]"
        $listGRP2_POST = $ui->table();
        $this->set("listGRP2_POST", $listGRP2_POST);
    }

    public function editInGroup1($id = null) {
        parent::evalParam($id, 'id');

        $bs = new TestfkBS();
        $instance = $bs->instance();
        $instance['Testfk']['title'] = "Prova in gruppi";
        $instance['Testfk']['description'] = "Prova 1 di modifica entity in gruppi";
        $instance['Testfk']['result'] = 1;
        $instance['Testfk']['test'] = 1;
        $instance['Testfk']['id'] = $id;

        // GRP1 e GRP2 prima
        $ui = new TestfkUI();
        $ui->json = false; // true
        $ui->groups = array("GRP1"); // "[\"GRP1\"]"
        $listGRP1_PRE = $ui->table();
        $this->set("listGRP1_PRE", $listGRP1_PRE);

        $ui = new TestfkUI();
        $ui->json = false; // true
        $ui->groups = array("GRP2"); // "[\"GRP2\"]"
        $listGRP2_PRE = $ui->table();
        $this->set("listGRP2_PRE", $listGRP2_PRE);

        // SALVATAGGIO
        $ui = new TestfkUI();
        $ui->json = false; // true
        $ui->groupsdel = array("GRP1"); // "[\"GRP1\"]"
        $id_save = $ui->edit($id, $instance);
        $this->set("id_save", $id_save);

        // GRP1 e GRP2 dopo
        $ui = new TestfkUI();
        $ui->json = false; // true
        $ui->groups = array("GRP1"); // "[\"GRP1\"]"
        $listGRP1_POST = $ui->table();
        $this->set("listGRP1_POST", $listGRP1_POST);

        $ui = new TestfkUI();
        $ui->json = false; // true
        $ui->groups = array("GRP2"); // "[\"GRP2\"]"
        $listGRP2_POST = $ui->table();
        $this->set("listGRP2_POST", $listGRP2_POST);
    }

    public function editInGroup2($id = null) {
        parent::evalParam($id, 'id');

        $bs = new TestfkBS();
        $instance = $bs->instance();
        $instance['Testfk']['title'] = "Prova in gruppi";
        $instance['Testfk']['description'] = "Prova 2 di modifica entity in gruppi";
        $instance['Testfk']['result'] = 1;
        $instance['Testfk']['test'] = 1;
        $instance['Testfk']['id'] = $id;

        // GRP1 e GRP2 prima
        $ui = new TestfkUI();
        $ui->json = false; // true
        $ui->groups = array("GRP1"); // "[\"GRP1\"]"
        $listGRP1_PRE = $ui->table();
        $this->set("listGRP1_PRE", $listGRP1_PRE);

        $ui = new TestfkUI();
        $ui->json = false; // true
        $ui->groups = array("GRP2"); // "[\"GRP2\"]"
        $listGRP2_PRE = $ui->table();
        $this->set("listGRP2_PRE", $listGRP2_PRE);

        // SALVATAGGIO
        $ui = new TestfkUI();
        $ui->json = false; // true
        $ui->groupssave = array("GRP1"); // "[\"GRP1\"]"
        $ui->groupsdel = array("GRP2"); // "[\"GRP2\"]"
        $id_save = $ui->edit($id, $instance);
        $this->set("id_save", $id_save);

        // GRP1 e GRP2 dopo
        $ui = new TestfkUI();
        $ui->json = false; // true
        $ui->groups = array("GRP1"); // "[\"GRP1\"]"
        $listGRP1_POST = $ui->table();
        $this->set("listGRP1_POST", $listGRP1_POST);

        $ui = new TestfkUI();
        $ui->json = false; // true
        $ui->groups = array("GRP2"); // "[\"GRP2\"]"
        $listGRP2_POST = $ui->table();
        $this->set("listGRP2_POST", $listGRP2_POST);
    }

    public function delInGroup($id = null) {
        parent::evalParam($id, 'id');

        // GRP1 e GRP2 prima
        $ui = new TestfkUI();
        $ui->json = false; // true
        $ui->groups = array("GRP1"); // "[\"GRP1\"]"
        $listGRP1_PRE = $ui->table();
        $this->set("listGRP1_PRE", $listGRP1_PRE);

        $ui = new TestfkUI();
        $ui->json = false; // true
        $ui->groups = array("GRP2"); // "[\"GRP2\"]"
        $listGRP2_PRE = $ui->table();
        $this->set("listGRP2_PRE", $listGRP2_PRE);

        // CANCELLAZIONE
        $ui = new TestfkUI();
        $ui->json = false; // true
        $opDel = $ui->delete($id);
        $this->set("opDel", $opDel);

        // GRP1 e GRP2 dopo
        $ui = new TestfkUI();
        $ui->json = false; // true
        $ui->groups = array("GRP1"); // "[\"GRP1\"]"
        $listGRP1_POST = $ui->table();
        $this->set("listGRP1_POST", $listGRP1_POST);

        $ui = new TestfkUI();
        $ui->json = false; // true
        $ui->groups = array("GRP2"); // "[\"GRP2\"]"
        $listGRP2_POST = $ui->table();
        $this->set("listGRP2_POST", $listGRP2_POST);
    }
}