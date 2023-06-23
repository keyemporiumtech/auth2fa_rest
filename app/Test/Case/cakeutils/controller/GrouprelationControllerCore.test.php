<?php
App::uses("GrouprelationBS", "modules/cakeutils/business");
App::uses("Codes", "Config/system");
App::uses("MysqlUtilityTest", "Test/utility");

class GrouprelationControllerCoreTest extends ControllerTestCase {

    function addRecord() {
        $bs = new GrouprelationBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "grouprelations");
            $bs = new GrouprelationBS();
            $obj = $bs->instance();
            $obj['Grouprelation']['cod'] = "mioCod";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "grouprelations", "cod='mioCod'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "grouprelations", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "grouprelations", "cod='mioCod'");
        }
    }

    function testGet() {
        $autoIncrement = $this->addRecord();
        $data = array(
            'id_grouprelation' => 1,
        );
        $post1 = $this->testAction('grouprelation/get', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $responseData = json_decode($post1, true);
        $this->assertEquals($responseData['id'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        $data = array(
            'id_grouprelation' => 9999999,
        );
        $post1 = $this->testAction('grouprelation/get', array(
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

    function testTable() {
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
        $post2 = $this->testAction('grouprelation/table', array(
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

    function testPaginate() {
        $bs = new GrouprelationBS();
        $sql = "SELECT COUNT(*) as num FROM grouprelations";
        $num = $bs->queryCount($sql, "num");

        $autoIncrement = null;
        $dbo = null;
        if ($num == 1) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "grouprelations");

            // insert
            $sql = "INSERT INTO grouprelations (id,cod,created) VALUES";
            $sql .= " (NULL, 'mioCod', CURRENT_TIMESTAMP)";
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
            $post1 = $this->testAction('grouprelation/table', array(
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
            $search = "SELECT * FROM grouprelations WHERE cod='mioCod'";
            $data = $dbo->query($search);
            $result = $data[0]['grouprelations'];
            $id = $result['id'];
            $this->assertEquals(!empty($data), true);
            $this->assertEquals($result['cod'], 'mioCod');

            // delete
            $bs = new GrouprelationBS();
            $id = $bs->delete($id);

            // reset
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "grouprelations", $autoIncrement);

            // verify reset
            MysqlUtilityTest::verifyDeleted($dbo, $this, "grouprelations", "cod='mioCod'");
        }
    }

    function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "grouprelations");

        // obj
        $bs = new GrouprelationBS();
        $obj = $bs->instance();
        $obj['Grouprelation']['cod'] = "mioCod";

        // save
        $data = array(
            'grouprelation' => json_encode($obj['Grouprelation']),
        );
        $post1 = $this->testAction('grouprelation/save', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $this->assertEquals(!empty($post1), 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        // search
        $search = "SELECT * FROM grouprelations WHERE cod='mioCod'";
        $data = $dbo->query($search);
        $result = $data[0]['grouprelations'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCod');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "grouprelations", "cod='mioCod'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "grouprelations", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "grouprelations", "cod='mioCod'");
    }

    function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new GrouprelationBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Grouprelation']['cod'] = "AltramioCod";

        // edit
        $data = array(
            'id_grouprelation' => $objNew['Grouprelation']['id'],
            'grouprelation' => json_encode($objNew['Grouprelation']),
        );
        $post1 = $this->testAction('grouprelation/edit', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $this->assertEquals(!empty($post1), 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        // test
        $bs = new GrouprelationBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Grouprelation']['cod'], 'AltramioCod');

        // reset
        $ui = new GrouprelationUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new GrouprelationBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Grouprelation']['cod'] == 'AltramioCod', false);
        $this->removeRecord($autoIncrement);
    }

    function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "grouprelations");

        // insert
        $sql = "INSERT INTO grouprelations (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCod', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM grouprelations WHERE cod='mioCod'";
        $data = $dbo->query($search);
        $result = $data[0]['grouprelations'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCod');

        // delete
        $data = array(
            'id_grouprelation' => $id,
        );
        $post1 = $this->testAction('grouprelation/delete', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $this->assertEquals(!empty($post1), 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "grouprelations", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "grouprelations", "cod='mioCod'");
    }
}