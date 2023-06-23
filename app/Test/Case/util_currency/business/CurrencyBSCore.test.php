<?php
App::uses("CurrencyBS", "modules/util_currency/business");
App::uses("MysqlUtilityTest", "Test/utility");

class CurrencyBSCoreTest extends CakeTestCase {

    public function testUnique() {
        $bs = new CurrencyBS();
        $obj = $bs->unique(1);
        $this->assertEquals($obj['Currency']['id'], 1);
    }

    public function testAll() {
        $bs = new CurrencyBS();
        $bs->addCondition("id", 1);
        $list = $bs->all();
        $this->assertEquals($list[0]['Currency']['id'], 1);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "currencys");

        // obj
        $bs = new CurrencyBS();
        $obj = $bs->instance();
        $obj['Currency']['cod'] = "mioCodTest";

        // save
        $bs = new CurrencyBS();
        $id = $bs->save($obj);

        // search
        $search = "SELECT * FROM currencys WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['currencys'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "currencys", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "currencys", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "currencys", "cod='mioCodTest'");
    }

    public function testEdit() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");

        // obj
        $id = 1;
        $bs = new CurrencyBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Currency']['cod'] = "OthermioCodTest";

        // edit
        $bs = new CurrencyBS();
        $id = $bs->save($objNew);

        // test
        $bs = new CurrencyBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Currency']['cod'], 'OthermioCodTest');

        // reset
        $bs = new CurrencyBS();
        $id = $bs->save($obj);

        // test
        $bs = new CurrencyBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Currency']['cod'] == 'OthermioCodTest', false);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "currencys");

        // insert
        $sql = "INSERT INTO currencys (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM currencys WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['currencys'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $bs = new CurrencyBS();
        $id = $bs->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "currencys", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "currencys", "cod='mioCodTest'");
    }
}