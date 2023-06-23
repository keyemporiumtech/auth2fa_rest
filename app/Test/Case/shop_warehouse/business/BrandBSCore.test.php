<?php
App::uses("BrandBS", "modules/shop_warehouse/business");
App::uses("MysqlUtilityTest", "Test/utility");

class BrandUICoreTest extends CakeTestCase {

    public function testUnique() {
        $bs = new BrandBS();
        $obj = $bs->unique(1);
        $this->assertEquals($obj['Brand']['id'], 1);
    }

    public function testAll() {
        $bs = new BrandBS();
        $bs->addCondition("id", 1);
        $list = $bs->all();
        $this->assertEquals($list[0]['Brand']['id'], 1);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "brands");

        // obj
        $bs = new BrandBS();
        $obj = $bs->instance();
        $obj['Brand']['cod'] = "mioCodTest";

        // save
        $bs = new BrandBS();
        $id = $bs->save($obj);

        // search
        $search = "SELECT * FROM brands WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['brands'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "brands", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "brands", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "brands", "cod='mioCodTest'");
    }

    public function testEdit() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");

        // obj
        $id = 1;
        $bs = new BrandBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Brand']['cod'] = "OthermioCodTest";

        // edit
        $bs = new BrandBS();
        $id = $bs->save($objNew);

        // test
        $bs = new BrandBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Brand']['cod'], 'OthermioCodTest');

        // reset
        $bs = new BrandBS();
        $id = $bs->save($obj);

        // test
        $bs = new BrandBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Brand']['cod'] == 'OthermioCodTest', false);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "brands");

        // insert
        $sql = "INSERT INTO brands (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM brands WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['brands'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $bs = new BrandBS();
        $id = $bs->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "brands", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "brands", "cod='mioCodTest'");
    }
}