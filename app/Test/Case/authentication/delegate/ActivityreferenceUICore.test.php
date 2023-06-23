<?php
App::uses("ActivityreferenceUI", "modules/authentication/delegate");
App::uses("ActivityreferenceBS", "modules/authentication/business");
App::uses("MysqlUtilityTest", "Test/utility");

class ActivityreferenceUICoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new ActivityreferenceBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "activityreferences");
            $bs = new ActivityreferenceBS();
            $obj = $bs->instance();
            $obj['Activityreference']['cod'] = "mioCodTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "activityreferences", "cod='mioCodTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "activityreferences", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "activityreferences", "cod='mioCodTest'");
        }
    }

    public function testGet() {
        $autoIncrement = $this->addRecord();
        $ui = new ActivityreferenceUI();
        $obj = $ui->get(1);
        $this->assertEquals($obj['Activityreference']['id'], 1);
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
        $ui = new ActivityreferenceUI();
        $paginator = $ui->table($conditions);
        $this->assertEquals($paginator['list'][0]['Activityreference']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "activityreferences");

        // obj
        $bs = new ActivityreferenceBS();
        $obj = $bs->instance();
        $obj['Activityreference']['cod'] = "mioCodTest";

        // save
        $ui = new ActivityreferenceUI();
        $id = $ui->save($obj);

        // search
        $search = "SELECT * FROM activityreferences WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['activityreferences'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "activityreferences", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "activityreferences", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "activityreferences", "cod='mioCodTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new ActivityreferenceBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Activityreference']['cod'] = "OthermioCodTest";

        // edit
        $ui = new ActivityreferenceUI();
        $id = $ui->edit($id, $objNew);

        // test
        $bs = new ActivityreferenceBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Activityreference']['cod'], 'OthermioCodTest');

        // reset
        $ui = new ActivityreferenceUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new ActivityreferenceBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Activityreference']['cod'] == 'OthermioCodTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "activityreferences");

        // insert
        $sql = "INSERT INTO activityreferences (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM activityreferences WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['activityreferences'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $ui = new ActivityreferenceUI();
        $ui->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "activityreferences", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "activityreferences", "cod='mioCodTest'");
    }
}