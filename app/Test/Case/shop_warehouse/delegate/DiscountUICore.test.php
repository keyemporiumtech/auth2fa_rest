<?php
App::uses("DiscountUI", "modules/shop_warehouse/delegate");
App::uses("DiscountBS", "modules/shop_warehouse/business");
App::uses("MysqlUtilityTest", "Test/utility");

class DiscountUICoreTest extends CakeTestCase {

    public function testGet() {
        $ui = new DiscountUI();
        $obj = $ui->get(1);
        $this->assertEquals($obj['Discount']['id'], 1);
    }

    public function testTable() {
        $condition = new DBCondition();
        $condition->key = "id";
        $condition->value = 1;
        $conditions = array(
            $condition,
        );
        $ui = new DiscountUI();
        $paginator = $ui->table($conditions);
        $this->assertEquals($paginator['list'][0]['Discount']['id'], 1);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "discounts");

        // obj
        $bs = new DiscountBS();
        $obj = $bs->instance();
        $obj['Discount']['cod'] = "mioCodTest";

        // save
        $ui = new DiscountUI();
        $id = $ui->save($obj);

        // search
        $search = "SELECT * FROM discounts WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['discounts'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "discounts", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "discounts", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "discounts", "cod='mioCodTest'");
    }

    public function testEdit() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");

        // obj
        $id = 1;
        $bs = new DiscountBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Discount']['cod'] = "OthermioCodTest";

        // edit
        $ui = new DiscountUI();
        $id = $ui->edit($id, $objNew);

        // test
        $bs = new DiscountBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Discount']['cod'], 'OthermioCodTest');

        // reset
        $ui = new DiscountUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new DiscountBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Discount']['cod'] == 'OthermioCodTest', false);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "discounts");

        // insert
        $sql = "INSERT INTO discounts (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM discounts WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['discounts'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $ui = new DiscountUI();
        $ui->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "discounts", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "discounts", "cod='mioCodTest'");
    }
}