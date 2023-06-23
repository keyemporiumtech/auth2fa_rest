<?php
App::uses("ProductattachmentUI", "modules/shop_warehouse/delegate");
App::uses("ProductattachmentBS", "modules/shop_warehouse/business");
App::uses("MysqlUtilityTest", "Test/utility");

class ProductattachmentUICoreTest extends CakeTestCase {

    public function testGet() {
        $ui = new ProductattachmentUI();
        $obj = $ui->get(1);
        $this->assertEquals($obj['Productattachment']['id'], 1);
    }

    public function testTable() {
        $condition = new DBCondition();
        $condition->key = "id";
        $condition->value = 1;
        $conditions = array(
            $condition,
        );
        $ui = new ProductattachmentUI();
        $paginator = $ui->table($conditions);
        $this->assertEquals($paginator['list'][0]['Productattachment']['id'], 1);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "productattachments");

        // obj
        $bs = new ProductattachmentBS();
        $obj = $bs->instance();
        $obj['Productattachment']['cod'] = "mioCodTest";

        // save
        $ui = new ProductattachmentUI();
        $id = $ui->save($obj);

        // search
        $search = "SELECT * FROM productattachments WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['productattachments'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "productattachments", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "productattachments", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "productattachments", "cod='mioCodTest'");
    }

    public function testEdit() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");

        // obj
        $id = 1;
        $bs = new ProductattachmentBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Productattachment']['cod'] = "OthermioCodTest";

        // edit
        $ui = new ProductattachmentUI();
        $id = $ui->edit($id, $objNew);

        // test
        $bs = new ProductattachmentBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Productattachment']['cod'], 'OthermioCodTest');

        // reset
        $ui = new ProductattachmentUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new ProductattachmentBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Productattachment']['cod'] == 'OthermioCodTest', false);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "productattachments");

        // insert
        $sql = "INSERT INTO productattachments (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM productattachments WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['productattachments'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $ui = new ProductattachmentUI();
        $ui->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "productattachments", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "productattachments", "cod='mioCodTest'");
    }
}