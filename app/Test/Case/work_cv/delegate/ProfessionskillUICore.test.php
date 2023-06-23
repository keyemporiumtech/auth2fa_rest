<?php
App::uses("ProfessionskillUI", "modules/work_cv/delegate");
App::uses("ProfessionskillBS", "modules/work_cv/business");
App::uses("MysqlUtilityTest", "Test/utility");

class ProfessionskillUICoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new ProfessionskillBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "professionskills");
            $bs = new ProfessionskillBS();
            $obj = $bs->instance();
            $obj['Professionskill']['cod'] = "mioCodTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "professionskills", "cod='mioCodTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "professionskills", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "professionskills", "cod='mioCodTest'");
        }
    }

    public function testGet() {
        $autoIncrement = $this->addRecord();
        $ui = new ProfessionskillUI();
        $obj = $ui->get(1);
        $this->assertEquals($obj['Professionskill']['id'], 1);
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
        $ui = new ProfessionskillUI();
        $paginator = $ui->table($conditions);
        $this->assertEquals($paginator['list'][0]['Professionskill']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "professionskills");

        // obj
        $bs = new ProfessionskillBS();
        $obj = $bs->instance();
        $obj['Professionskill']['cod'] = "mioCodTest";

        // save
        $ui = new ProfessionskillUI();
        $id = $ui->save($obj);

        // search
        $search = "SELECT * FROM professionskills WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['professionskills'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "professionskills", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "professionskills", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "professionskills", "cod='mioCodTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new ProfessionskillBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Professionskill']['cod'] = "OthermioCodTest";

        // edit
        $ui = new ProfessionskillUI();
        $id = $ui->edit($id, $objNew);

        // test
        $bs = new ProfessionskillBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Professionskill']['cod'], 'OthermioCodTest');

        // reset
        $ui = new ProfessionskillUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new ProfessionskillBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Professionskill']['cod'] == 'OthermioCodTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "professionskills");

        // insert
        $sql = "INSERT INTO professionskills (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM professionskills WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['professionskills'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $ui = new ProfessionskillUI();
        $ui->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "professionskills", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "professionskills", "cod='mioCodTest'");
    }
}