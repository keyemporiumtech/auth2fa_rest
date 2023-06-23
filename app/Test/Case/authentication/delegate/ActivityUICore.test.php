<?php
App::uses("ActivityUI", "modules/authentication/delegate");
App::uses("ActivityBS", "modules/authentication/business");
App::uses("MysqlUtilityTest", "Test/utility");

class ActivityUICoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new ActivityBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "activities");
            $bs = new ActivityBS();
            $obj = $bs->instance();
            $obj['Activity']['namecod'] = "mioNamecodTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "activities", "namecod='mioNamecodTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "activities", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "activities", "namecod='mioNamecodTest'");
        }
    }

    public function testGet() {
        $autoIncrement = $this->addRecord();
        $ui = new ActivityUI();
        $obj = $ui->get(1);
        $this->assertEquals($obj['Activity']['id'], 1);
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
        $ui = new ActivityUI();
        $paginator = $ui->table($conditions);
        $this->assertEquals($paginator['list'][0]['Activity']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "activities");

        // obj
        $bs = new ActivityBS();
        $obj = $bs->instance();
        $obj['Activity']['namecod'] = "mioNamecodTest";

        // save
        $ui = new ActivityUI();
        $id = $ui->save($obj);

        // search
        $search = "SELECT * FROM activities WHERE namecod='mioNamecodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['activities'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['namecod'], 'mioNamecodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "activities", "namecod='mioNamecodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "activities", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "activities", "namecod='mioNamecodTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new ActivityBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Activity']['namecod'] = "OthermioNamecodTest";

        // edit
        $ui = new ActivityUI();
        $id = $ui->edit($id, $objNew);

        // test
        $bs = new ActivityBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Activity']['namecod'], 'OthermioNamecodTest');

        // reset
        $ui = new ActivityUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new ActivityBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Activity']['namecod'] == 'OthermioNamecodTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "activities");

        // insert
        $sql = "INSERT INTO activities (id,namecod,created) VALUES";
        $sql .= " (NULL, 'mioNamecodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM activities WHERE namecod='mioNamecodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['activities'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['namecod'], 'mioNamecodTest');

        // delete
        $ui = new ActivityUI();
        $ui->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "activities", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "activities", "namecod='mioNamecodTest'");
    }
}