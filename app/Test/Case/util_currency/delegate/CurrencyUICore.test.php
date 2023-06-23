<?php
App::uses("CurrencyUI", "modules/util_currency/delegate");
App::uses("CurrencyBS", "modules/util_currency/business");
App::uses("MysqlUtilityTest", "Test/utility");

class CurrencyUICoreTest extends CakeTestCase {

    public function testGet() {
        $ui = new CurrencyUI();
        $obj = $ui->get(1);
        $this->assertEquals($obj['Currency']['id'], 1);
    }

    public function testTable() {
        $condition = new DBCondition();
        $condition->key = "id";
        $condition->value = 1;
        $conditions = array(
            $condition,
        );
        $ui = new CurrencyUI();
        $paginator = $ui->table($conditions);
        $this->assertEquals($paginator['list'][0]['Currency']['id'], 1);
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
        $ui = new CurrencyUI();
        $id = $ui->save($obj);

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
        $ui = new CurrencyUI();
        $id = $ui->edit($id, $objNew);

        // test
        $bs = new CurrencyBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Currency']['cod'], 'OthermioCodTest');

        // reset
        $ui = new CurrencyUI();
        $id = $ui->edit($id, $obj);

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
        $ui = new CurrencyUI();
        $ui->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "currencys", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "currencys", "cod='mioCodTest'");
    }
}