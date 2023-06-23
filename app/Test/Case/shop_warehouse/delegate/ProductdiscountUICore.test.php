<?php
App::uses("ProductdiscountUI", "modules/shop_warehouse/delegate");
App::uses("ProductdiscountBS", "modules/shop_warehouse/business");
App::uses("MysqlUtilityTest", "Test/utility");

class ProductdiscountUICoreTest extends CakeTestCase {

    public function testGet() {
        $ui = new ProductdiscountUI();
        $obj = $ui->get(1);
        $this->assertEquals($obj['Productdiscount']['id'], 1);
    }

    public function testTable() {
        $condition = new DBCondition();
        $condition->key = "id";
        $condition->value = 1;
        $conditions = array(
            $condition,
        );
        $ui = new ProductdiscountUI();
        $paginator = $ui->table($conditions);
        $this->assertEquals($paginator['list'][0]['Productdiscount']['id'], 1);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "productdiscounts");

        // obj
        $bs = new ProductdiscountBS();
        $obj = $bs->instance();
        $obj['Productdiscount']['cod'] = "mioCodTest";

        // save
        $ui = new ProductdiscountUI();
        $id = $ui->save($obj);

        // search
        $search = "SELECT * FROM productdiscounts WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['productdiscounts'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "productdiscounts", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "productdiscounts", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "productdiscounts", "cod='mioCodTest'");
    }

    public function testEdit() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");

        // obj
        $id = 1;
        $bs = new ProductdiscountBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Productdiscount']['cod'] = "OthermioCodTest";

        // edit
        $ui = new ProductdiscountUI();
        $id = $ui->edit($id, $objNew);

        // test
        $bs = new ProductdiscountBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Productdiscount']['cod'], 'OthermioCodTest');

        // reset
        $ui = new ProductdiscountUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new ProductdiscountBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Productdiscount']['cod'] == 'OthermioCodTest', false);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "productdiscounts");

        // insert
        $sql = "INSERT INTO productdiscounts (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM productdiscounts WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['productdiscounts'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $ui = new ProductdiscountUI();
        $ui->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "productdiscounts", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "productdiscounts", "cod='mioCodTest'");
    }
}