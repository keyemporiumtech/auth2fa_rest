<?php
App::uses("ServiceattachmentUI", "modules/shop_warehouse/delegate");
App::uses("ServiceattachmentBS", "modules/shop_warehouse/business");
App::uses("MysqlUtilityTest", "Test/utility");

class ServiceattachmentUICoreTest extends CakeTestCase {

    public function testGet() {
        $ui = new ServiceattachmentUI();
        $obj = $ui->get(1);
        $this->assertEquals($obj['Serviceattachment']['id'], 1);
    }

    public function testTable() {
        $condition = new DBCondition();
        $condition->key = "id";
        $condition->value = 1;
        $conditions = array(
            $condition,
        );
        $ui = new ServiceattachmentUI();
        $paginator = $ui->table($conditions);
        $this->assertEquals($paginator['list'][0]['Serviceattachment']['id'], 1);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "serviceattachments");

        // obj
        $bs = new ServiceattachmentBS();
        $obj = $bs->instance();
        $obj['Serviceattachment']['cod'] = "mioCodTest";

        // save
        $ui = new ServiceattachmentUI();
        $id = $ui->save($obj);

        // search
        $search = "SELECT * FROM serviceattachments WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['serviceattachments'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "serviceattachments", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "serviceattachments", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "serviceattachments", "cod='mioCodTest'");
    }

    public function testEdit() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");

        // obj
        $id = 1;
        $bs = new ServiceattachmentBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Serviceattachment']['cod'] = "OthermioCodTest";

        // edit
        $ui = new ServiceattachmentUI();
        $id = $ui->edit($id, $objNew);

        // test
        $bs = new ServiceattachmentBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Serviceattachment']['cod'], 'OthermioCodTest');

        // reset
        $ui = new ServiceattachmentUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new ServiceattachmentBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Serviceattachment']['cod'] == 'OthermioCodTest', false);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "serviceattachments");

        // insert
        $sql = "INSERT INTO serviceattachments (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM serviceattachments WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['serviceattachments'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $ui = new ServiceattachmentUI();
        $ui->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "serviceattachments", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "serviceattachments", "cod='mioCodTest'");
    }
}