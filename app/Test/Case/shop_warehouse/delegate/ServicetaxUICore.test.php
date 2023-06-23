<?php
App::uses("ServicetaxUI", "modules/shop_warehouse/delegate");
App::uses("ServicetaxBS", "modules/shop_warehouse/business");
App::uses("MysqlUtilityTest", "Test/utility");

class ServicetaxUICoreTest extends CakeTestCase {

    public function testGet() {
        $ui = new ServicetaxUI();
        $obj = $ui->get(1);
        $this->assertEquals($obj['Servicetax']['id'], 1);
    }

    public function testTable() {
        $condition = new DBCondition();
        $condition->key = "id";
        $condition->value = 1;
        $conditions = array(
            $condition,
        );
        $ui = new ServicetaxUI();
        $paginator = $ui->table($conditions);
        $this->assertEquals($paginator['list'][0]['Servicetax']['id'], 1);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "servicetaxs");

        // obj
        $bs = new ServicetaxBS();
        $obj = $bs->instance();
        $obj['Servicetax']['cod'] = "mioCodTest";

        // save
        $ui = new ServicetaxUI();
        $id = $ui->save($obj);

        // search
        $search = "SELECT * FROM servicetaxs WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['servicetaxs'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "servicetaxs", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "servicetaxs", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "servicetaxs", "cod='mioCodTest'");
    }

    public function testEdit() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");

        // obj
        $id = 1;
        $bs = new ServicetaxBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Servicetax']['cod'] = "OthermioCodTest";

        // edit
        $ui = new ServicetaxUI();
        $id = $ui->edit($id, $objNew);

        // test
        $bs = new ServicetaxBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Servicetax']['cod'], 'OthermioCodTest');

        // reset
        $ui = new ServicetaxUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new ServicetaxBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Servicetax']['cod'] == 'OthermioCodTest', false);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "servicetaxs");

        // insert
        $sql = "INSERT INTO servicetaxs (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM servicetaxs WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['servicetaxs'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $ui = new ServicetaxUI();
        $ui->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "servicetaxs", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "servicetaxs", "cod='mioCodTest'");
    }
}