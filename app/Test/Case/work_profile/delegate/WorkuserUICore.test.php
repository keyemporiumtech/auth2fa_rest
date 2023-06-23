<?php
App::uses("WorkuserUI", "modules/work_profile/delegate");
App::uses("WorkuserBS", "modules/work_profile/business");
App::uses("MysqlUtilityTest", "Test/utility");

class WorkuserUICoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new WorkuserBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "workusers");
            $bs = new WorkuserBS();
            $obj = $bs->instance();
            $obj['Workuser']['cod'] = "mioCodTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "workusers", "cod='mioCodTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "workusers", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "workusers", "cod='mioCodTest'");
        }
    }

    public function testGet() {
        $autoIncrement = $this->addRecord();
        $ui = new WorkuserUI();
        $obj = $ui->get(1);
        $this->assertEquals($obj['Workuser']['id'], 1);
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
        $ui = new WorkuserUI();
        $paginator = $ui->table($conditions);
        $this->assertEquals($paginator['list'][0]['Workuser']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "workusers");

        // obj
        $bs = new WorkuserBS();
        $obj = $bs->instance();
        $obj['Workuser']['cod'] = "mioCodTest";

        // save
        $ui = new WorkuserUI();
        $id = $ui->save($obj);

        // search
        $search = "SELECT * FROM workusers WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['workusers'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "workusers", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "workusers", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "workusers", "cod='mioCodTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new WorkuserBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Workuser']['cod'] = "OthermioCodTest";

        // edit
        $ui = new WorkuserUI();
        $id = $ui->edit($id, $objNew);

        // test
        $bs = new WorkuserBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Workuser']['cod'], 'OthermioCodTest');

        // reset
        $ui = new WorkuserUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new WorkuserBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Workuser']['cod'] == 'OthermioCodTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "workusers");

        // insert
        $sql = "INSERT INTO workusers (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM workusers WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['workusers'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $ui = new WorkuserUI();
        $ui->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "workusers", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "workusers", "cod='mioCodTest'");
    }
}