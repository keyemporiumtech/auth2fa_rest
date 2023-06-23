<?php
App::uses("ServiceUI", "modules/shop_warehouse/delegate");
App::uses("ServiceBS", "modules/shop_warehouse/business");
App::uses("MysqlUtilityTest", "Test/utility");

class ServiceUICoreTest extends CakeTestCase {

    public function testGet() {
        $ui = new ServiceUI();
        $obj = $ui->get(1);
        $this->assertEquals($obj['Service']['id'], 1);
    }

    public function testTable() {
        $condition = new DBCondition();
        $condition->key = "id";
        $condition->value = 1;
        $conditions = array(
            $condition,
        );
        $ui = new ServiceUI();
        $paginator = $ui->table($conditions);
        $this->assertEquals($paginator['list'][0]['Service']['id'], 1);
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
        $ui = new ServiceUI();
        $id = $ui->save($obj);

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
        $ui = new ServiceUI();
        $id = $ui->edit($id, $objNew);

        // test
        $bs = new ServiceBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Service']['cod'], 'OthermioCodTest');

        // reset
        $ui = new ServiceUI();
        $id = $ui->edit($id, $obj);

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
        $ui = new ServiceUI();
        $ui->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "services", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "services", "cod='mioCodTest'");
    }
}