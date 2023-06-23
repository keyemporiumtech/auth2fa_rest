<?php
App::uses("PocketattachmentUI", "modules/shop_warehouse/delegate");
App::uses("PocketattachmentBS", "modules/shop_warehouse/business");
App::uses("MysqlUtilityTest", "Test/utility");

class PocketattachmentUICoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new PocketattachmentBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "pocketattachments");
            $bs = new PocketattachmentBS();
            $obj = $bs->instance();
            $obj['Pocketattachment']['cod'] = "mioCodTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "pocketattachments", "cod='mioCodTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "pocketattachments", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "pocketattachments", "cod='mioCodTest'");
        }
    }

    public function testGet() {
        $autoIncrement = $this->addRecord();
        $ui = new PocketattachmentUI();
        $obj = $ui->get(1);
        $this->assertEquals($obj['Pocketattachment']['id'], 1);
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
        $ui = new PocketattachmentUI();
        $paginator = $ui->table($conditions);
        $this->assertEquals($paginator['list'][0]['Pocketattachment']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "pocketattachments");

        // obj
        $bs = new PocketattachmentBS();
        $obj = $bs->instance();
        $obj['Pocketattachment']['cod'] = "mioCodTest";

        // save
        $ui = new PocketattachmentUI();
        $id = $ui->save($obj);

        // search
        $search = "SELECT * FROM pocketattachments WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['pocketattachments'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "pocketattachments", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "pocketattachments", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "pocketattachments", "cod='mioCodTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new PocketattachmentBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Pocketattachment']['cod'] = "OthermioCodTest";

        // edit
        $ui = new PocketattachmentUI();
        $id = $ui->edit($id, $objNew);

        // test
        $bs = new PocketattachmentBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Pocketattachment']['cod'], 'OthermioCodTest');

        // reset
        $ui = new PocketattachmentUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new PocketattachmentBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Pocketattachment']['cod'] == 'OthermioCodTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "pocketattachments");

        // insert
        $sql = "INSERT INTO pocketattachments (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM pocketattachments WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['pocketattachments'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $ui = new PocketattachmentUI();
        $ui->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "pocketattachments", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "pocketattachments", "cod='mioCodTest'");
    }
}