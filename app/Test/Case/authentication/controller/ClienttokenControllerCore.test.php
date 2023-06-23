<?php
App::uses("ClienttokenBS", "modules/authentication/business");
App::uses("Codes", "Config/system");
App::uses("MysqlUtilityTest", "Test/utility");

class ClienttokenControllerCoreTest extends ControllerTestCase {

    public function addRecord() {
        $bs = new ClienttokenBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "clienttokens");
            $bs = new ClienttokenBS();
            $obj = $bs->instance();
            $obj['Clienttoken']['appname'] = "mioAppnameTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "clienttokens", "appname='mioAppnameTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "clienttokens", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "clienttokens", "appname='mioAppnameTest'");
        }
    }

    public function testGet() {
        $autoIncrement = $this->addRecord();
        $data = array(
            'id_clienttoken' => 1,
        );
        $post1 = $this->testAction('clienttoken/get', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $responseData = json_decode($post1, true);
        $this->assertEquals($responseData['id'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        $data = array(
            'id_clienttoken' => 9999999,
        );
        $post1 = $this->testAction('clienttoken/get', array(
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
        $post2 = $this->testAction('clienttoken/table', array(
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
        $bs = new ClienttokenBS();
        $sql = "SELECT COUNT(*) as num FROM clienttokens";
        $num = $bs->queryCount($sql, "num");

        $autoIncrement = null;
        $dbo = null;
        if ($num == 1) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "clienttokens");

            // insert
            $sql = "INSERT INTO clienttokens (id,appname,created) VALUES";
            $sql .= " (NULL, 'mioAppnameTest', CURRENT_TIMESTAMP)";
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
            $post1 = $this->testAction('clienttoken/table', array(
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
            $search = "SELECT * FROM clienttokens WHERE appname='mioAppnameTest'";
            $data = $dbo->query($search);
            $result = $data[0]['clienttokens'];
            $id = $result['id'];
            $this->assertEquals(!empty($data), true);
            $this->assertEquals($result['appname'], 'mioAppnameTest');

            // delete
            $bs = new ClienttokenBS();
            $id = $bs->delete($id);

            // reset
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "clienttokens", $autoIncrement);

            // verify reset
            MysqlUtilityTest::verifyDeleted($dbo, $this, "clienttokens", "appname='mioAppnameTest'");
        }
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "clienttokens");

        // obj
        $bs = new ClienttokenBS();
        $obj = $bs->instance();
        $obj['Clienttoken']['appname'] = "mioAppnameTest";

        // save
        $data = array(
            'clienttoken' => json_encode($obj['Clienttoken']),
        );
        $post1 = $this->testAction('clienttoken/save', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $this->assertEquals(!empty($post1), 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        // search
        $search = "SELECT * FROM clienttokens WHERE appname='mioAppnameTest'";
        $data = $dbo->query($search);
        $result = $data[0]['clienttokens'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['appname'], 'mioAppnameTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "clienttokens", "appname='mioAppnameTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "clienttokens", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "clienttokens", "appname='mioAppnameTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new ClienttokenBS();
        $bs->addPropertyDao("flgDecrypt", true);
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Clienttoken']['appname'] = "AltramioAppnameTest";

        // edit
        $data = array(
            'id_clienttoken' => $objNew['Clienttoken']['id'],
            'clienttoken' => json_encode($objNew['Clienttoken']),
        );
        $post1 = $this->testAction('clienttoken/edit', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $this->assertEquals(!empty($post1), 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        // test
        $bs = new ClienttokenBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Clienttoken']['appname'], 'AltramioAppnameTest');

        // reset
        $ui = new ClienttokenUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new ClienttokenBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Clienttoken']['appname'] == 'AltramioAppnameTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "clienttokens");

        // insert
        $sql = "INSERT INTO clienttokens (id,appname,created) VALUES";
        $sql .= " (NULL, 'mioAppnameTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM clienttokens WHERE appname='mioAppnameTest'";
        $data = $dbo->query($search);
        $result = $data[0]['clienttokens'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['appname'], 'mioAppnameTest');

        // delete
        $data = array(
            'id_clienttoken' => $id,
        );
        $post1 = $this->testAction('clienttoken/delete', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $this->assertEquals(!empty($post1), 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "clienttokens", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "clienttokens", "appname='mioAppnameTest'");
    }
}