<?php
App::uses("ServicediscountBS", "modules/shop_warehouse/business");
App::uses("MysqlUtilityTest", "Test/utility");

class ServicediscountUICoreTest extends CakeTestCase {

    public function testUnique() {
        $bs = new ServicediscountBS();
        $obj = $bs->unique(1);
        $this->assertEquals($obj['Servicediscount']['id'], 1);
    }

    public function testAll() {
        $bs = new ServicediscountBS();
        $bs->addCondition("id", 1);
        $list = $bs->all();
        $this->assertEquals($list[0]['Servicediscount']['id'], 1);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "servicediscounts");

        // obj
        $bs = new ServicediscountBS();
        $obj = $bs->instance();
        $obj['Servicediscount']['cod'] = "mioCodTest";

        // save
        $bs = new ServicediscountBS();
        $id = $bs->save($obj);

        // search
        $search = "SELECT * FROM servicediscounts WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['servicediscounts'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "servicediscounts", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "servicediscounts", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "servicediscounts", "cod='mioCodTest'");
    }

    public function testEdit() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");

        // obj
        $id = 1;
        $bs = new ServicediscountBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Servicediscount']['cod'] = "OthermioCodTest";

        // edit
        $bs = new ServicediscountBS();
        $id = $bs->save($objNew);

        // test
        $bs = new ServicediscountBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Servicediscount']['cod'], 'OthermioCodTest');

        // reset
        $bs = new ServicediscountBS();
        $id = $bs->save($obj);

        // test
        $bs = new ServicediscountBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Servicediscount']['cod'] == 'OthermioCodTest', false);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "servicediscounts");

        // insert
        $sql = "INSERT INTO servicediscounts (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM servicediscounts WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['servicediscounts'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $bs = new ServicediscountBS();
        $id = $bs->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "servicediscounts", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "servicediscounts", "cod='mioCodTest'");
    }
}