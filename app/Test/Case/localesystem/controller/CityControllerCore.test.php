<?php
App::uses("CityBS", "modules/localesystem/business");
App::uses("Codes", "Config/system");
App::uses("MysqlUtilityTest", "Test/utility");

class CityControllerCoreTest extends ControllerTestCase {

    public function addRecord() {
        $bs = new CityBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "cities");
            $bs = new CityBS();
            $obj = $bs->instance();
            $obj['City']['cod'] = "mioCodTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "cities", "cod='mioCodTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "cities", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "cities", "cod='mioCodTest'");
        }
    }

    public function testGet() {
        $autoIncrement = $this->addRecord();
        $data = array(
            'id_city' => 1,
        );
        $post1 = $this->testAction('city/get', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $responseData = json_decode($post1, true);
        $this->assertEquals($responseData['id'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        $data = array(
            'id_city' => 9999999,
        );
        $post1 = $this->testAction('city/get', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));
        $responseData = json_decode($post1, true);
        $this->assertEquals($responseData, null);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], -1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 500);
        $this->assertEquals($this->controller->response->header('_headers', '')['exceptioncod'], Codes::get("EXCEPTION_GENERIC"));
        $this->removeRecord($autoIncrement);
    }

    public function testTable() {
        $autoIncrement = $this->addRecord();
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
        $post2 = $this->testAction('city/table', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $paginator = json_decode($post2, true);
        $this->assertEquals(count($paginator['list']), 0);
        $this->assertEquals($paginator['count'], 0);
        $this->assertEquals($paginator['pages'], 0);
        $this->removeRecord($autoIncrement);
    }

    public function testPaginate() {
        $bs = new CityBS();
        $sql = "SELECT COUNT(*) as num FROM cities";
        $num = $bs->queryCount($sql, "num");

        $autoIncrement = null;
        $dbo = null;
        if ($num == 1) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "cities");

            // insert
            $sql = "INSERT INTO cities (id,cod,created) VALUES";
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
            $post1 = $this->testAction('city/table', array(
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
            $search = "SELECT * FROM cities WHERE cod='mioCodTest'";
            $data = $dbo->query($search);
            $result = $data[0]['cities'];
            $id = $result['id'];
            $this->assertEquals(!empty($data), true);
            $this->assertEquals($result['cod'], 'mioCodTest');

            // delete
            $bs = new CityBS();
            $id = $bs->delete($id);

            // reset
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "cities", $autoIncrement);

            // verify reset
            MysqlUtilityTest::verifyDeleted($dbo, $this, "cities", "cod='mioCodTest'");
        }
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "cities");

        // obj
        $bs = new CityBS();
        $obj = $bs->instance();
        $obj['City']['cod'] = "mioCodTest";

        // save
        $data = array(
            'city' => json_encode($obj['City']),
        );
        $post1 = $this->testAction('city/save', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $this->assertEquals(!empty($post1), 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        // search
        $search = "SELECT * FROM cities WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['cities'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "cities", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "cities", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "cities", "cod='mioCodTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new CityBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['City']['cod'] = "AltramioCodTest";

        // edit
        $data = array(
            'id_city' => $objNew['City']['id'],
            'city' => json_encode($objNew['City']),
        );
        $post1 = $this->testAction('city/edit', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $this->assertEquals(!empty($post1), 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        // test
        $bs = new CityBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['City']['cod'], 'AltramioCodTest');

        // reset
        $ui = new CityUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new CityBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['City']['cod'] == 'AltramioCodTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "cities");

        // insert
        $sql = "INSERT INTO cities (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM cities WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['cities'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $data = array(
            'id_city' => $id,
        );
        $post1 = $this->testAction('city/delete', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $this->assertEquals(!empty($post1), 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "cities", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "cities", "cod='mioCodTest'");
    }
}