<?php
App::uses("ReservationsettingUI", "modules/shop_warehouse/delegate");
App::uses("ReservationsettingBS", "modules/shop_warehouse/business");
App::uses("MysqlUtilityTest", "Test/utility");

class ReservationsettingUICoreTest extends CakeTestCase {

    public function testGet() {
        $ui = new ReservationsettingUI();
        $obj = $ui->get(1);
        $this->assertEquals($obj['Reservationsetting']['id'], 1);
    }

    public function testTable() {
        $condition = new DBCondition();
        $condition->key = "id";
        $condition->value = 1;
        $conditions = array(
            $condition,
        );
        $ui = new ReservationsettingUI();
        $paginator = $ui->table($conditions);
        $this->assertEquals($paginator['list'][0]['Reservationsetting']['id'], 1);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "reservationsettings");

        // obj
        $bs = new ReservationsettingBS();
        $obj = $bs->instance();
        $obj['Reservationsetting']['cod'] = "mioCodTest";

        // save
        $ui = new ReservationsettingUI();
        $id = $ui->save($obj);

        // search
        $search = "SELECT * FROM reservationsettings WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['reservationsettings'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "reservationsettings", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "reservationsettings", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "reservationsettings", "cod='mioCodTest'");
    }

    public function testEdit() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");

        // obj
        $id = 1;
        $bs = new ReservationsettingBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Reservationsetting']['cod'] = "OthermioCodTest";

        // edit
        $ui = new ReservationsettingUI();
        $id = $ui->edit($id, $objNew);

        // test
        $bs = new ReservationsettingBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Reservationsetting']['cod'], 'OthermioCodTest');

        // reset
        $ui = new ReservationsettingUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new ReservationsettingBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Reservationsetting']['cod'] == 'OthermioCodTest', false);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "reservationsettings");

        // insert
        $sql = "INSERT INTO reservationsettings (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM reservationsettings WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['reservationsettings'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $ui = new ReservationsettingUI();
        $ui->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "reservationsettings", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "reservationsettings", "cod='mioCodTest'");
    }
}