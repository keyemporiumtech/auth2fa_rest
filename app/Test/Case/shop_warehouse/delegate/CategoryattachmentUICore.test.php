<?php
App::uses("CategoryattachmentUI", "modules/shop_warehouse/delegate");
App::uses("CategoryattachmentBS", "modules/shop_warehouse/business");
App::uses("MysqlUtilityTest", "Test/utility");

class CategoryattachmentUICoreTest extends CakeTestCase {

    public function testGet() {
        $ui = new CategoryattachmentUI();
        $obj = $ui->get(1);
        $this->assertEquals($obj['Categoryattachment']['id'], 1);
    }

    public function testTable() {
        $condition = new DBCondition();
        $condition->key = "id";
        $condition->value = 1;
        $conditions = array(
            $condition,
        );
        $ui = new CategoryattachmentUI();
        $paginator = $ui->table($conditions);
        $this->assertEquals($paginator['list'][0]['Categoryattachment']['id'], 1);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "categoryattachments");

        // obj
        $bs = new CategoryattachmentBS();
        $obj = $bs->instance();
        $obj['Categoryattachment']['cod'] = "mioCodTest";

        // save
        $ui = new CategoryattachmentUI();
        $id = $ui->save($obj);

        // search
        $search = "SELECT * FROM categoryattachments WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['categoryattachments'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "categoryattachments", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "categoryattachments", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "categoryattachments", "cod='mioCodTest'");
    }

    public function testEdit() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");

        // obj
        $id = 1;
        $bs = new CategoryattachmentBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Categoryattachment']['cod'] = "OthermioCodTest";

        // edit
        $ui = new CategoryattachmentUI();
        $id = $ui->edit($id, $objNew);

        // test
        $bs = new CategoryattachmentBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Categoryattachment']['cod'], 'OthermioCodTest');

        // reset
        $ui = new CategoryattachmentUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new CategoryattachmentBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Categoryattachment']['cod'] == 'OthermioCodTest', false);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "categoryattachments");

        // insert
        $sql = "INSERT INTO categoryattachments (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM categoryattachments WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['categoryattachments'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $ui = new CategoryattachmentUI();
        $ui->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "categoryattachments", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "categoryattachments", "cod='mioCodTest'");
    }
}