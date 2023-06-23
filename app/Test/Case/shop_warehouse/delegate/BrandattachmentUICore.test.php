<?php
App::uses("BrandattachmentUI", "modules/shop_warehouse/delegate");
App::uses("BrandattachmentBS", "modules/shop_warehouse/business");
App::uses("MysqlUtilityTest", "Test/utility");

class BrandattachmentUICoreTest extends CakeTestCase {

    public function testGet() {
        $ui = new BrandattachmentUI();
        $obj = $ui->get(1);
        $this->assertEquals($obj['Brandattachment']['id'], 1);
    }

    public function testTable() {
        $condition = new DBCondition();
        $condition->key = "id";
        $condition->value = 1;
        $conditions = array(
            $condition,
        );
        $ui = new BrandattachmentUI();
        $paginator = $ui->table($conditions);
        $this->assertEquals($paginator['list'][0]['Brandattachment']['id'], 1);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "brandattachments");

        // obj
        $bs = new BrandattachmentBS();
        $obj = $bs->instance();
        $obj['Brandattachment']['cod'] = "mioCodTest";

        // save
        $ui = new BrandattachmentUI();
        $id = $ui->save($obj);

        // search
        $search = "SELECT * FROM brandattachments WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['brandattachments'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "brandattachments", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "brandattachments", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "brandattachments", "cod='mioCodTest'");
    }

    public function testEdit() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");

        // obj
        $id = 1;
        $bs = new BrandattachmentBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Brandattachment']['cod'] = "OthermioCodTest";

        // edit
        $ui = new BrandattachmentUI();
        $id = $ui->edit($id, $objNew);

        // test
        $bs = new BrandattachmentBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Brandattachment']['cod'], 'OthermioCodTest');

        // reset
        $ui = new BrandattachmentUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new BrandattachmentBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Brandattachment']['cod'] == 'OthermioCodTest', false);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "brandattachments");

        // insert
        $sql = "INSERT INTO brandattachments (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM brandattachments WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['brandattachments'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $ui = new BrandattachmentUI();
        $ui->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "brandattachments", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "brandattachments", "cod='mioCodTest'");
    }
}