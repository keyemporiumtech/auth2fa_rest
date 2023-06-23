<?php
App::uses("ProductreservesettingBS", "modules/shop_warehouse/business");
App::uses("MysqlUtilityTest", "Test/utility");

class ProductreservesettingUICoreTest extends CakeTestCase {

    public function testUnique() {
        $bs = new ProductreservesettingBS();
        $obj = $bs->unique(1);
        $this->assertEquals($obj['Productreservesetting']['id'], 1);
    }

    public function testAll() {
        $bs = new ProductreservesettingBS();
        $bs->addCondition("id", 1);
        $list = $bs->all();
        $this->assertEquals($list[0]['Productreservesetting']['id'], 1);
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
        $bs = new ProductreservesettingBS();
        $id = $bs->save($obj);

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
        $bs = new ProductreservesettingBS();
        $id = $bs->save($objNew);

        // test
        $bs = new ProductreservesettingBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Productreservesetting']['cod'], 'OthermioCodTest');

        // reset
        $bs = new ProductreservesettingBS();
        $id = $bs->save($obj);

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
        $bs = new ProductreservesettingBS();
        $id = $bs->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "productreservesettings", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "productreservesettings", "cod='mioCodTest'");
    }
}