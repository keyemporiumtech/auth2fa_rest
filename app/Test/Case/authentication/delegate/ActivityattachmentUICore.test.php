<?php
App::uses("ActivityattachmentUI", "modules/authentication/delegate");
App::uses("ActivityattachmentBS", "modules/authentication/business");
App::uses("MysqlUtilityTest", "Test/utility");

class ActivityattachmentUICoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new ActivityattachmentBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "activityattachments");
            $bs = new ActivityattachmentBS();
            $obj = $bs->instance();
            $obj['Activityattachment']['cod'] = "mioCodTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "activityattachments", "cod='mioCodTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "activityattachments", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "activityattachments", "cod='mioCodTest'");
        }
    }

    public function testGet() {
        $autoIncrement = $this->addRecord();
        $ui = new ActivityattachmentUI();
        $obj = $ui->get(1);
        $this->assertEquals($obj['Activityattachment']['id'], 1);
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
        $ui = new ActivityattachmentUI();
        $paginator = $ui->table($conditions);
        $this->assertEquals($paginator['list'][0]['Activityattachment']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "activityattachments");

        // obj
        $bs = new ActivityattachmentBS();
        $obj = $bs->instance();
        $obj['Activityattachment']['cod'] = "mioCodTest";

        // save
        $ui = new ActivityattachmentUI();
        $id = $ui->save($obj);

        // search
        $search = "SELECT * FROM activityattachments WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['activityattachments'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "activityattachments", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "activityattachments", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "activityattachments", "cod='mioCodTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new ActivityattachmentBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Activityattachment']['cod'] = "OthermioCodTest";

        // edit
        $ui = new ActivityattachmentUI();
        $id = $ui->edit($id, $objNew);

        // test
        $bs = new ActivityattachmentBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Activityattachment']['cod'], 'OthermioCodTest');

        // reset
        $ui = new ActivityattachmentUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new ActivityattachmentBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Activityattachment']['cod'] == 'OthermioCodTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "activityattachments");

        // insert
        $sql = "INSERT INTO activityattachments (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM activityattachments WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['activityattachments'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $ui = new ActivityattachmentUI();
        $ui->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "activityattachments", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "activityattachments", "cod='mioCodTest'");
    }
}