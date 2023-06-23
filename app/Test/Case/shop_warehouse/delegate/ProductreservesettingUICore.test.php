<?php
App::uses("ProductreservesettingUI", "modules/shop_warehouse/delegate");
App::uses("ProductreservesettingBS", "modules/shop_warehouse/business");
App::uses("MysqlUtilityTest", "Test/utility");

class ProductreservesettingUICoreTest extends CakeTestCase {

    public function testGet() {
        $ui = new ProductreservesettingUI();
        $obj = $ui->get(1);
        $this->assertEquals($obj['Productreservesetting']['id'], 1);
    }

    public function testTable() {
        $condition = new DBCondition();
        $condition->key = "id";
        $condition->value = 1;
        $conditions = array(
            $condition,
        );
        $ui = new ProductreservesettingUI();
        $paginator = $ui->table($conditions);
        $this->assertEquals($paginator['list'][0]['Productreservesetting']['id'], 1);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "productreservesettings");

        // obj
        $bs = new ProductreservesettingBS();
        $obj = $bs->instance();
        $obj['Productreservesetting']['cod'] = "mioCodTest";

        // save
        $ui = new ProductreservesettingUI();
        $id = $ui->save($obj);

        // search
        $search = "SELECT * FROM productreservesettings WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['productreservesettings'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "productreservesettings", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "productreservesettings", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "productreservesettings", "cod='mioCodTest'");
    }

    public function testEdit() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");

        // obj
        $id = 1;
        $bs = new ProductreservesettingBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Productreservesetting']['cod'] = "OthermioCodTest";

        // edit
        $ui = new ProductreservesettingUI();
        $id = $ui->edit($id, $objNew);

        // test
        $bs = new ProductreservesettingBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Productreservesetting']['cod'], 'OthermioCodTest');

        // reset
        $ui = new ProductreservesettingUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new ProductreservesettingBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Productreservesetting']['cod'] == 'OthermioCodTest', false);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "productreservesettings");

        // insert
        $sql = "INSERT INTO productreservesettings (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM productreservesettings WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['productreservesettings'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $ui = new ProductreservesettingUI();
        $ui->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "productreservesettings", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "productreservesettings", "cod='mioCodTest'");
    }
}