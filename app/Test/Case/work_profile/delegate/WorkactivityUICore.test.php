<?php
App::uses("WorkactivityUI", "modules/work_profile/delegate");
App::uses("WorkactivityBS", "modules/work_profile/business");
App::uses("MysqlUtilityTest", "Test/utility");

class WorkactivityUICoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new WorkactivityBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "workactivities");
            $bs = new WorkactivityBS();
            $obj = $bs->instance();
            $obj['Workactivity']['cod'] = "mioCodTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "workactivities", "cod='mioCodTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "workactivities", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "workactivities", "cod='mioCodTest'");
        }
    }

    public function testGet() {
        $autoIncrement = $this->addRecord();
        $ui = new WorkactivityUI();
        $obj = $ui->get(1);
        $this->assertEquals($obj['Workactivity']['id'], 1);
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
        $ui = new WorkactivityUI();
        $paginator = $ui->table($conditions);
        $this->assertEquals($paginator['list'][0]['Workactivity']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "workactivities");

        // obj
        $bs = new WorkactivityBS();
        $obj = $bs->instance();
        $obj['Workactivity']['cod'] = "mioCodTest";

        // save
        $ui = new WorkactivityUI();
        $id = $ui->save($obj);

        // search
        $search = "SELECT * FROM workactivities WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['workactivities'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "workactivities", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "workactivities", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "workactivities", "cod='mioCodTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new WorkactivityBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Workactivity']['cod'] = "OthermioCodTest";

        // edit
        $ui = new WorkactivityUI();
        $id = $ui->edit($id, $objNew);

        // test
        $bs = new WorkactivityBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Workactivity']['cod'], 'OthermioCodTest');

        // reset
        $ui = new WorkactivityUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new WorkactivityBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Workactivity']['cod'] == 'OthermioCodTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "workactivities");

        // insert
        $sql = "INSERT INTO workactivities (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM workactivities WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['workactivities'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $ui = new WorkactivityUI();
        $ui->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "workactivities", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "workactivities", "cod='mioCodTest'");
    }
}