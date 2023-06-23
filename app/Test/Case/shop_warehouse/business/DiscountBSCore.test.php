<?php
App::uses("DiscountBS", "modules/shop_warehouse/business");
App::uses("MysqlUtilityTest", "Test/utility");

class DiscountUICoreTest extends CakeTestCase {

    public function testUnique() {
        $bs = new DiscountBS();
        $obj = $bs->unique(1);
        $this->assertEquals($obj['Discount']['id'], 1);
    }

    public function testAll() {
        $bs = new DiscountBS();
        $bs->addCondition("id", 1);
        $list = $bs->all();
        $this->assertEquals($list[0]['Discount']['id'], 1);
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
        $bs = new DiscountBS();
        $id = $bs->save($obj);

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
        $bs = new DiscountBS();
        $id = $bs->save($objNew);

        // test
        $bs = new DiscountBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Discount']['cod'], 'OthermioCodTest');

        // reset
        $bs = new DiscountBS();
        $id = $bs->save($obj);

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
        $bs = new DiscountBS();
        $id = $bs->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "discounts", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "discounts", "cod='mioCodTest'");
    }
}