<?php
App::uses("ServiceBS", "modules/shop_warehouse/business");
App::uses("MysqlUtilityTest", "Test/utility");

class ServiceUICoreTest extends CakeTestCase {

    public function testUnique() {
        $bs = new ServiceBS();
        $obj = $bs->unique(1);
        $this->assertEquals($obj['Service']['id'], 1);
    }

    public function testAll() {
        $bs = new ServiceBS();
        $bs->addCondition("id", 1);
        $list = $bs->all();
        $this->assertEquals($list[0]['Service']['id'], 1);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "services");

        // obj
        $bs = new ServiceBS();
        $obj = $bs->instance();
        $obj['Service']['cod'] = "mioCodTest";

        // save
        $bs = new ServiceBS();
        $id = $bs->save($obj);

        // search
        $search = "SELECT * FROM services WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['services'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "services", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "services", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "services", "cod='mioCodTest'");
    }

    public function testEdit() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");

        // obj
        $id = 1;
        $bs = new ServiceBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Service']['cod'] = "OthermioCodTest";

        // edit
        $bs = new ServiceBS();
        $id = $bs->save($objNew);

        // test
        $bs = new ServiceBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Service']['cod'], 'OthermioCodTest');

        // reset
        $bs = new ServiceBS();
        $id = $bs->save($obj);

        // test
        $bs = new ServiceBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Service']['cod'] == 'OthermioCodTest', false);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "services");

        // insert
        $sql = "INSERT INTO services (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM services WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['services'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $bs = new ServiceBS();
        $id = $bs->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "services", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "services", "cod='mioCodTest'");
    }
}