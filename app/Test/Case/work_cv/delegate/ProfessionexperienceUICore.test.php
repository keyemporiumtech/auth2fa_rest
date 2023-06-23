<?php
App::uses("ProfessionexperienceUI", "modules/work_cv/delegate");
App::uses("ProfessionexperienceBS", "modules/work_cv/business");
App::uses("MysqlUtilityTest", "Test/utility");

class ProfessionexperienceUICoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new ProfessionexperienceBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "professionexperiences");
            $bs = new ProfessionexperienceBS();
            $obj = $bs->instance();
            $obj['Professionexperience']['cod'] = "mioCodTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "professionexperiences", "cod='mioCodTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "professionexperiences", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "professionexperiences", "cod='mioCodTest'");
        }
    }

    public function testGet() {
        $autoIncrement = $this->addRecord();
        $ui = new ProfessionexperienceUI();
        $obj = $ui->get(1);
        $this->assertEquals($obj['Professionexperience']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testTable() {
        $autoIncrement = $this->addRecord();
        $condition = new DBCondition();
        $condition->key = "id";
        $condition->value = 1;
        $conditions = array(
            $condition,
        );
        $ui = new ProfessionexperienceUI();
        $paginator = $ui->table($conditions);
        $this->assertEquals($paginator['list'][0]['Professionexperience']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "professionexperiences");

        // obj
        $bs = new ProfessionexperienceBS();
        $obj = $bs->instance();
        $obj['Professionexperience']['cod'] = "mioCodTest";

        // save
        $ui = new ProfessionexperienceUI();
        $id = $ui->save($obj);

        // search
        $search = "SELECT * FROM professionexperiences WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['professionexperiences'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "professionexperiences", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "professionexperiences", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "professionexperiences", "cod='mioCodTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new ProfessionexperienceBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Professionexperience']['cod'] = "OthermioCodTest";

        // edit
        $ui = new ProfessionexperienceUI();
        $id = $ui->edit($id, $objNew);

        // test
        $bs = new ProfessionexperienceBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Professionexperience']['cod'], 'OthermioCodTest');

        // reset
        $ui = new ProfessionexperienceUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new ProfessionexperienceBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Professionexperience']['cod'] == 'OthermioCodTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "professionexperiences");

        // insert
        $sql = "INSERT INTO professionexperiences (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM professionexperiences WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['professionexperiences'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $ui = new ProfessionexperienceUI();
        $ui->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "professionexperiences", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "professionexperiences", "cod='mioCodTest'");
    }
}