<?php
App::uses("UserreportBS", "modules/authentication/business");
App::uses("Codes", "Config/system");
App::uses("MysqlUtilityTest", "Test/utility");

class UserreportControllerCoreTest extends ControllerTestCase {

    public function addRecord() {
        $bs = new UserreportBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "userreports");
            $bs = new UserreportBS();
            $obj = $bs->instance();
            $obj['Userreport']['codoperation'] = "mioCodoperationTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "userreports", "codoperation='mioCodoperationTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "userreports", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "userreports", "codoperation='mioCodoperationTest'");
        }
    }

    public function testGet() {
        $autoIncrement = $this->addRecord();
        $data = array(
            'id_userreport' => 1,
        );
        $post1 = $this->testAction('userreport/get', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $responseData = json_decode($post1, true);
        $this->assertEquals($responseData['id'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        $data = array(
            'id_userreport' => 9999999,
        );
        $post1 = $this->testAction('userreport/get', array(
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
        $post2 = $this->testAction('userreport/table', array(
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
        $bs = new UserreportBS();
        $sql = "SELECT COUNT(*) as num FROM userreports";
        $num = $bs->queryCount($sql, "num");

        $autoIncrement = null;
        $dbo = null;
        if ($num == 1) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "userreports");

            // insert
            $sql = "INSERT INTO userreports (id,codoperation,created) VALUES";
            $sql .= " (NULL, 'mioCodoperationTest', CURRENT_TIMESTAMP)";
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
            $post1 = $this->testAction('userreport/table', array(
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
            $search = "SELECT * FROM userreports WHERE codoperation='mioCodoperationTest'";
            $data = $dbo->query($search);
            $result = $data[0]['userreports'];
            $id = $result['id'];
            $this->assertEquals(!empty($data), true);
            $this->assertEquals($result['codoperation'], 'mioCodoperationTest');

            // delete
            $bs = new UserreportBS();
            $id = $bs->delete($id);

            // reset
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "userreports", $autoIncrement);

            // verify reset
            MysqlUtilityTest::verifyDeleted($dbo, $this, "userreports", "codoperation='mioCodoperationTest'");
        }
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "userreports");

        // obj
        $bs = new UserreportBS();
        $obj = $bs->instance();
        $obj['Userreport']['codoperation'] = "mioCodoperationTest";

        // save
        $data = array(
            'userreport' => json_encode($obj['Userreport']),
        );
        $post1 = $this->testAction('userreport/save', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $this->assertEquals(!empty($post1), 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        // search
        $search = "SELECT * FROM userreports WHERE codoperation='mioCodoperationTest'";
        $data = $dbo->query($search);
        $result = $data[0]['userreports'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['codoperation'], 'mioCodoperationTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "userreports", "codoperation='mioCodoperationTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "userreports", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "userreports", "codoperation='mioCodoperationTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new UserreportBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Userreport']['codoperation'] = "AltramioCodoperationTest";

        // edit
        $data = array(
            'id_userreport' => $objNew['Userreport']['id'],
            'userreport' => json_encode($objNew['Userreport']),
        );

        $post1 = $this->testAction('userreport/edit', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $this->assertEquals(!empty($post1), 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        // test
        $bs = new UserreportBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Userreport']['codoperation'], 'AltramioCodoperationTest');

        // reset
        $ui = new UserreportUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new UserreportBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Userreport']['codoperation'] == 'AltramioCodoperationTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "userreports");

        // insert
        $sql = "INSERT INTO userreports (id,codoperation,created) VALUES";
        $sql .= " (NULL, 'mioCodoperationTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM userreports WHERE codoperation='mioCodoperationTest'";
        $data = $dbo->query($search);
        $result = $data[0]['userreports'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['codoperation'], 'mioCodoperationTest');

        // delete
        $data = array(
            'id_userreport' => $id,
        );
        $post1 = $this->testAction('userreport/delete', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $this->assertEquals(!empty($post1), 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "userreports", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "userreports", "codoperation='mioCodoperationTest'");
    }
}