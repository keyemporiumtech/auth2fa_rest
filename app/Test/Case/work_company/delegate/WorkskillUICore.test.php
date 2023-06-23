<?php
App::uses("WorkskillUI", "modules/work_company/delegate");
App::uses("WorkskillBS", "modules/work_company/business");
App::uses("MysqlUtilityTest", "Test/utility");

class WorkskillUICoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new WorkskillBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "workskills");
            $bs = new WorkskillBS();
            $obj = $bs->instance();
            $obj['Workskill']['cod'] = "mioCodTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "workskills", "cod='mioCodTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "workskills", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "workskills", "cod='mioCodTest'");
        }
    }

    public function testGet() {
        $autoIncrement = $this->addRecord();
        $ui = new WorkskillUI();
        $obj = $ui->get(1);
        $this->assertEquals($obj['Workskill']['id'], 1);
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
        $ui = new WorkskillUI();
        $paginator = $ui->table($conditions);
        $this->assertEquals($paginator['list'][0]['Workskill']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "workskills");

        // obj
        $bs = new WorkskillBS();
        $obj = $bs->instance();
        $obj['Workskill']['cod'] = "mioCodTest";

        // save
        $ui = new WorkskillUI();
        $id = $ui->save($obj);

        // search
        $search = "SELECT * FROM workskills WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['workskills'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "workskills", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "workskills", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "workskills", "cod='mioCodTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new WorkskillBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Workskill']['cod'] = "OthermioCodTest";

        // edit
        $ui = new WorkskillUI();
        $id = $ui->edit($id, $objNew);

        // test
        $bs = new WorkskillBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Workskill']['cod'], 'OthermioCodTest');

        // reset
        $ui = new WorkskillUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new WorkskillBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Workskill']['cod'] == 'OthermioCodTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "workskills");

        // insert
        $sql = "INSERT INTO workskills (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM workskills WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['workskills'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $ui = new WorkskillUI();
        $ui->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "workskills", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "workskills", "cod='mioCodTest'");
    }
}