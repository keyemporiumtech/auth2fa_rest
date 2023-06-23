<?php
App::uses("ConfirmoperationBS", "modules/authentication/business");
App::uses("Codes", "Config/system");
App::uses("MysqlUtilityTest", "Test/utility");

class ConfirmoperationControllerCoreTest extends ControllerTestCase {

    public function addRecord() {
        $bs = new ConfirmoperationBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "confirmoperations");
            $bs = new ConfirmoperationBS();
            $obj = $bs->instance();
            $obj['Confirmoperation']['codoperation'] = "mioCodoperation";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "confirmoperations", "codoperation='mioCodoperation'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "confirmoperations", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "confirmoperations", "codoperation='mioCodoperation'");
        }
    }

    public function testGet() {
        $autoIncrement = $this->addRecord();
        $data = array(
            'id_confirmoperation' => 1,
        );
        $post1 = $this->testAction('confirmoperation/get', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $responseData = json_decode($post1, true);
        $this->assertEquals($responseData['id'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        $data = array(
            'id_confirmoperation' => 9999999,
        );
        $post1 = $this->testAction('confirmoperation/get', array(
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
        $post2 = $this->testAction('confirmoperation/table', array(
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
        $bs = new ConfirmoperationBS();
        $sql = "SELECT COUNT(*) as num FROM confirmoperations";
        $num = $bs->queryCount($sql, "num");

        $autoIncrement = null;
        $dbo = null;
        if ($num == 1) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "confirmoperations");

            // insert
            $sql = "INSERT INTO confirmoperations (id,codoperation,created) VALUES";
            $sql .= " (NULL, 'mioCodoperation', CURRENT_TIMESTAMP)";
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
            $post1 = $this->testAction('confirmoperation/table', array(
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
            $search = "SELECT * FROM confirmoperations WHERE codoperation='mioCodoperation'";
            $data = $dbo->query($search);
            $result = $data[0]['confirmoperations'];
            $id = $result['id'];
            $this->assertEquals(!empty($data), true);
            $this->assertEquals($result['codoperation'], 'mioCodoperation');

            // delete
            $bs = new ConfirmoperationBS();
            $id = $bs->delete($id);

            // reset
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "confirmoperations", $autoIncrement);

            // verify reset
            MysqlUtilityTest::verifyDeleted($dbo, $this, "confirmoperations", "codoperation='mioCodoperation'");
        }
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "confirmoperations");

        // obj
        $bs = new ConfirmoperationBS();
        $obj = $bs->instance();
        $obj['Confirmoperation']['codoperation'] = "mioCodoperation";

        // save
        $data = array(
            'confirmoperation' => json_encode($obj['Confirmoperation']),
        );
        $post1 = $this->testAction('confirmoperation/save', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $this->assertEquals(!empty($post1), 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        // search
        $search = "SELECT * FROM confirmoperations WHERE codoperation='mioCodoperation'";
        $data = $dbo->query($search);
        $result = $data[0]['confirmoperations'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['codoperation'], 'mioCodoperation');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "confirmoperations", "codoperation='mioCodoperation'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "confirmoperations", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "confirmoperations", "codoperation='mioCodoperation'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new ConfirmoperationBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Confirmoperation']['codoperation'] = "AltramioCodoperation";

        // edit
        $data = array(
            'id_confirmoperation' => $objNew['Confirmoperation']['id'],
            'confirmoperation' => json_encode($objNew['Confirmoperation']),
        );
        $post1 = $this->testAction('confirmoperation/edit', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $this->assertEquals(!empty($post1), 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        // test
        $bs = new ConfirmoperationBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Confirmoperation']['codoperation'], 'AltramioCodoperation');

        // reset
        $ui = new ConfirmoperationUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new ConfirmoperationBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Confirmoperation']['codoperation'] == 'AltramioCodoperation', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "confirmoperations");

        // insert
        $sql = "INSERT INTO confirmoperations (id,codoperation,created) VALUES";
        $sql .= " (NULL, 'mioCodoperation', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM confirmoperations WHERE codoperation='mioCodoperation'";
        $data = $dbo->query($search);
        $result = $data[0]['confirmoperations'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['codoperation'], 'mioCodoperation');

        // delete
        $data = array(
            'id_confirmoperation' => $id,
        );
        $post1 = $this->testAction('confirmoperation/delete', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $this->assertEquals(!empty($post1), 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "confirmoperations", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "confirmoperations", "codoperation='mioCodoperation'");
    }
}