<?php
App::uses("MultilanguageBS", "modules/localesystem/business");
App::uses("Codes", "Config/system");
App::uses("MysqlUtilityTest", "Test/utility");

class MultilanguageControllerCoreTest extends ControllerTestCase {

    public function addRecord() {
        $bs = new MultilanguageBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "multilanguages");
            $bs = new MultilanguageBS();
            $obj = $bs->instance();
            $obj['Multilanguage']['tablename'] = "miaTablenameTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "multilanguages", "tablename='miaTablenameTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "multilanguages", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "multilanguages", "tablename='miaTablenameTest'");
        }
    }

    public function testGet() {
        $autoIncrement = $this->addRecord();
        $data = array(
            'id_multilanguage' => 1,
        );
        $post1 = $this->testAction('multilanguage/get', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $responseData = json_decode($post1, true);
        $this->assertEquals($responseData['id'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        $data = array(
            'id_multilanguage' => 9999999,
        );
        $post1 = $this->testAction('multilanguage/get', array(
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
        $post2 = $this->testAction('multilanguage/table', array(
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
        $bs = new MultilanguageBS();
        $sql = "SELECT COUNT(*) as num FROM multilanguages";
        $num = $bs->queryCount($sql, "num");

        $autoIncrement = null;
        $dbo = null;
        if ($num == 1) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "multilanguages");

            // insert
            $sql = "INSERT INTO multilanguages (id,tablename,created) VALUES";
            $sql .= " (NULL, 'miaTablenameTest', CURRENT_TIMESTAMP)";
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
            $post1 = $this->testAction('multilanguage/table', array(
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
            $search = "SELECT * FROM multilanguages WHERE tablename='miaTablenameTest'";
            $data = $dbo->query($search);
            $result = $data[0]['multilanguages'];
            $id = $result['id'];
            $this->assertEquals(!empty($data), true);
            $this->assertEquals($result['tablename'], 'miaTablenameTest');

            // delete
            $bs = new MultilanguageBS();
            $id = $bs->delete($id);

            // reset
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "multilanguages", $autoIncrement);

            // verify reset
            MysqlUtilityTest::verifyDeleted($dbo, $this, "multilanguages", "tablename='miaTablenameTest'");
        }
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "multilanguages");

        // obj
        $bs = new MultilanguageBS();
        $obj = $bs->instance();
        $obj['Multilanguage']['tablename'] = "miaTablenameTest";

        // save
        $data = array(
            'multilanguage' => json_encode($obj['Multilanguage']),
        );
        $post1 = $this->testAction('multilanguage/save', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $this->assertEquals(!empty($post1), 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        // search
        $search = "SELECT * FROM multilanguages WHERE tablename='miaTablenameTest'";
        $data = $dbo->query($search);
        $result = $data[0]['multilanguages'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['tablename'], 'miaTablenameTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "multilanguages", "tablename='miaTablenameTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "multilanguages", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "multilanguages", "tablename='miaTablenameTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new MultilanguageBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Multilanguage']['tablename'] = "AltramiaTablenameTest";

        // edit
        $data = array(
            'id_multilanguage' => $objNew['Multilanguage']['id'],
            'multilanguage' => json_encode($objNew['Multilanguage']),
        );
        $post1 = $this->testAction('multilanguage/edit', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $this->assertEquals(!empty($post1), 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        // test
        $bs = new MultilanguageBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Multilanguage']['tablename'], 'AltramiaTablenameTest');

        // reset
        $ui = new MultilanguageUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new MultilanguageBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Multilanguage']['tablename'] == 'AltramiaTablenameTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "multilanguages");

        // insert
        $sql = "INSERT INTO multilanguages (id,tablename,created) VALUES";
        $sql .= " (NULL, 'miaTablenameTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM multilanguages WHERE tablename='miaTablenameTest'";
        $data = $dbo->query($search);
        $result = $data[0]['multilanguages'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['tablename'], 'miaTablenameTest');

        // delete
        $data = array(
            'id_multilanguage' => $id,
        );
        $post1 = $this->testAction('multilanguage/delete', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $this->assertEquals(!empty($post1), 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "multilanguages", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "multilanguages", "tablename='miaTablenameTest'");
    }
}