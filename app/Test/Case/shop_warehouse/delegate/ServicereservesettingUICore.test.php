<?php
App::uses("ServicereservesettingUI", "modules/shop_warehouse/delegate");
App::uses("ServicereservesettingBS", "modules/shop_warehouse/business");
App::uses("MysqlUtilityTest", "Test/utility");

class ServicereservesettingUICoreTest extends CakeTestCase {

    public function testGet() {
        $ui = new ServicereservesettingUI();
        $obj = $ui->get(1);
        $this->assertEquals($obj['Servicereservesetting']['id'], 1);
    }

    public function testTable() {
        $condition = new DBCondition();
        $condition->key = "id";
        $condition->value = 1;
        $conditions = array(
            $condition,
        );
        $ui = new ServicereservesettingUI();
        $paginator = $ui->table($conditions);
        $this->assertEquals($paginator['list'][0]['Servicereservesetting']['id'], 1);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "servicereservesettings");

        // obj
        $bs = new ServicereservesettingBS();
        $obj = $bs->instance();
        $obj['Servicereservesetting']['cod'] = "mioCodTest";

        // save
        $ui = new ServicereservesettingUI();
        $id = $ui->save($obj);

        // search
        $search = "SELECT * FROM servicereservesettings WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['servicereservesettings'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "servicereservesettings", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "servicereservesettings", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "servicereservesettings", "cod='mioCodTest'");
    }

    public function testEdit() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");

        // obj
        $id = 1;
        $bs = new ServicereservesettingBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Servicereservesetting']['cod'] = "OthermioCodTest";

        // edit
        $ui = new ServicereservesettingUI();
        $id = $ui->edit($id, $objNew);

        // test
        $bs = new ServicereservesettingBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Servicereservesetting']['cod'], 'OthermioCodTest');

        // reset
        $ui = new ServicereservesettingUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new ServicereservesettingBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Servicereservesetting']['cod'] == 'OthermioCodTest', false);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "servicereservesettings");

        // insert
        $sql = "INSERT INTO servicereservesettings (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM servicereservesettings WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['servicereservesettings'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $ui = new ServicereservesettingUI();
        $ui->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "servicereservesettings", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "servicereservesettings", "cod='mioCodTest'");
    }
}