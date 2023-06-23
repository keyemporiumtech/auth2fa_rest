<?php
App::uses("ReservationsettingBS", "modules/shop_warehouse/business");
App::uses("Codes", "Config/system");
App::uses("MysqlUtilityTest", "Test/utility");

class ReservationsettingControllerCoreTest extends ControllerTestCase {

    public function testGet() {
        $data = array(
            'id_reservationsetting' => 1,
        );
        $post1 = $this->testAction('reservationsetting/get', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $responseData = json_decode($post1, true);
        $this->assertEquals($responseData['id'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        $data = array(
            'id_reservationsetting' => 9999999,
        );
        $post1 = $this->testAction('reservationsetting/get', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));
        $responseData = json_decode($post1, true);
        $this->assertEquals($responseData, null);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], -1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 500);
        $this->assertEquals($this->controller->response->header('_headers', '')['exceptioncod'], Codes::get("EXCEPTION_GENERIC"));
    }

    public function testTable() {
        // CONDITIONS
        $condition = new DBCondition();
        $condition->key = "id";
        $condition->value = 99999;
        $conditions = array(
            $condition,
        );

        $data = array(
            'filters' => json_encode($conditions),
        );
        $post2 = $this->testAction('reservationsetting/table', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $paginator = json_decode($post2, true);
        $this->assertEquals(count($paginator['list']), 0);
        $this->assertEquals($paginator['count'], 0);
        $this->assertEquals($paginator['pages'], 0);
    }

    public function testPaginate() {
        $bs = new ReservationsettingBS();
        $sql = "SELECT COUNT(*) as num FROM reservationsettings";
        $num = $bs->queryCount($sql, "num");

        $autoIncrement = null;
        $dbo = null;
        if ($num == 1) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "reservationsettings");

            // insert
            $sql = "INSERT INTO reservationsettings (id,cod,created) VALUES";
            $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
            $data = $dbo->query($sql);
        } elseif ($num == 0) {
            $this->assertEquals($num, 0);
        } else {

            // pagination
            $paginate = new DBPaginate();
            $paginate->limit = 1;
            $paginate->page = 2;

            $data = array(
                'paginate' => json_encode($paginate),
            );
            $post1 = $this->testAction('reservationsetting/table', array(
                'data' => $data,
                'return' => 'view',
                'method' => 'POST',
            ));

            $paginator = json_decode($post1, true);
            $this->assertEquals($paginator['list'][0]['id'] > 1, true);
            $this->assertEquals(count($paginator['list']), 1);
            $this->assertEquals($paginator['count'], $num);
            $this->assertEquals($paginator['pages'], $num);
        }
        if (!empty($autoIncrement)) {
            // search
            $search = "SELECT * FROM reservationsettings WHERE cod='mioCodTest'";
            $data = $dbo->query($search);
            $result = $data[0]['reservationsettings'];
            $id = $result['id'];
            $this->assertEquals(!empty($data), true);
            $this->assertEquals($result['cod'], 'mioCodTest');

            // delete
            $bs = new ReservationsettingBS();
            $id = $bs->delete($id);

            // reset
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "reservationsettings", $autoIncrement);

            // verify reset
            MysqlUtilityTest::verifyDeleted($dbo, $this, "reservationsettings", "cod='mioCodTest'");
        }
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
        $data = array(
            'reservationsetting' => json_encode($obj['Reservationsetting']),
        );
        $post1 = $this->testAction('reservationsetting/save', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $this->assertEquals(!empty($post1), 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

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
        $objNew['Reservationsetting']['cod'] = "AltramioCodTest";

        // edit
        $data = array(
            'id_reservationsetting' => $objNew['Reservationsetting']['id'],
            'reservationsetting' => json_encode($objNew['Reservationsetting']),
        );
        $post1 = $this->testAction('reservationsetting/edit', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $this->assertEquals(!empty($post1), 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        // test
        $bs = new ReservationsettingBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Reservationsetting']['cod'], 'AltramioCodTest');

        // reset
        $ui = new ReservationsettingUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new ReservationsettingBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Reservationsetting']['cod'] == 'AltramioCodTest', false);
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
        $data = array(
            'id_reservationsetting' => $id,
        );
        $post1 = $this->testAction('reservationsetting/delete', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $this->assertEquals(!empty($post1), 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "reservationsettings", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "reservationsettings", "cod='mioCodTest'");
    }
}