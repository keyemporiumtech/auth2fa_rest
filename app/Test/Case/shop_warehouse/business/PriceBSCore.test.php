<?php
App::uses("PriceBS", "modules/shop_warehouse/business");
App::uses("MysqlUtilityTest", "Test/utility");

class PriceUICoreTest extends CakeTestCase {

    public function testUnique() {
        $bs = new PriceBS();
        $obj = $bs->unique(1);
        $this->assertEquals($obj['Price']['id'], 1);
    }

    public function testAll() {
        $bs = new PriceBS();
        $bs->addCondition("id", 1);
        $list = $bs->all();
        $this->assertEquals($list[0]['Price']['id'], 1);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "prices");

        // obj
        $bs = new PriceBS();
        $obj = $bs->instance();
        $obj['Price']['cod'] = "mioCodTest";

        // save
        $bs = new PriceBS();
        $id = $bs->save($obj);

        // search
        $search = "SELECT * FROM prices WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['prices'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "prices", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "prices", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "prices", "cod='mioCodTest'");
    }

    public function testEdit() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");

        // obj
        $id = 1;
        $bs = new PriceBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Price']['cod'] = "OthermioCodTest";

        // edit
        $bs = new PriceBS();
        $id = $bs->save($objNew);

        // test
        $bs = new PriceBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Price']['cod'], 'OthermioCodTest');

        // reset
        $bs = new PriceBS();
        $id = $bs->save($obj);

        // test
        $bs = new PriceBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Price']['cod'] == 'OthermioCodTest', false);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "prices");

        // insert
        $sql = "INSERT INTO prices (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM prices WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['prices'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $bs = new PriceBS();
        $id = $bs->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "prices", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "prices", "cod='mioCodTest'");
    }
}