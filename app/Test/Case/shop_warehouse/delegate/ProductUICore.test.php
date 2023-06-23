<?php
App::uses("ProductUI", "modules/shop_warehouse/delegate");
App::uses("ProductBS", "modules/shop_warehouse/business");
App::uses("MysqlUtilityTest", "Test/utility");

class ProductUICoreTest extends CakeTestCase {

    public function testGet() {
        $ui = new ProductUI();
        $obj = $ui->get(1);
        $this->assertEquals($obj['Product']['id'], 1);
    }

    public function testTable() {
        $condition = new DBCondition();
        $condition->key = "id";
        $condition->value = 1;
        $conditions = array(
            $condition,
        );
        $ui = new ProductUI();
        $paginator = $ui->table($conditions);
        $this->assertEquals($paginator['list'][0]['Product']['id'], 1);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "products");

        // obj
        $bs = new ProductBS();
        $obj = $bs->instance();
        $obj['Product']['cod'] = "mioCodTest";

        // save
        $ui = new ProductUI();
        $id = $ui->save($obj);

        // search
        $search = "SELECT * FROM products WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['products'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "products", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "products", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "products", "cod='mioCodTest'");
    }

    public function testEdit() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");

        // obj
        $id = 1;
        $bs = new ProductBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Product']['cod'] = "OthermioCodTest";

        // edit
        $ui = new ProductUI();
        $id = $ui->edit($id, $objNew);

        // test
        $bs = new ProductBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Product']['cod'], 'OthermioCodTest');

        // reset
        $ui = new ProductUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new ProductBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Product']['cod'] == 'OthermioCodTest', false);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "products");

        // insert
        $sql = "INSERT INTO products (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM products WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['products'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $ui = new ProductUI();
        $ui->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "products", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "products", "cod='mioCodTest'");
    }
}