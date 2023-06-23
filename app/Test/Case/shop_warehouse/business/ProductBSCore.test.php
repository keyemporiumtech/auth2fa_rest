<?php
App::uses("ProductBS", "modules/shop_warehouse/business");
App::uses("MysqlUtilityTest", "Test/utility");

class ProductUICoreTest extends CakeTestCase {

    public function testUnique() {
        $bs = new ProductBS();
        $obj = $bs->unique(1);
        $this->assertEquals($obj['Product']['id'], 1);
    }

    public function testAll() {
        $bs = new ProductBS();
        $bs->addCondition("id", 1);
        $list = $bs->all();
        $this->assertEquals($list[0]['Product']['id'], 1);
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
        $bs = new ProductBS();
        $id = $bs->save($obj);

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
        $bs = new ProductBS();
        $id = $bs->save($objNew);

        // test
        $bs = new ProductBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Product']['cod'], 'OthermioCodTest');

        // reset
        $bs = new ProductBS();
        $id = $bs->save($obj);

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
        $bs = new ProductBS();
        $id = $bs->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "products", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "products", "cod='mioCodTest'");
    }
}