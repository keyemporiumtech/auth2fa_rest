<?php
App::uses("ActivityBS", "modules/authentication/business");
App::uses("Codes", "Config/system");
App::uses("MysqlUtilityTest", "Test/utility");

class ActivityControllerCoreTest extends ControllerTestCase {

    public function addRecord() {
        $bs = new ActivityBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "activities");
            $bs = new ActivityBS();
            $obj = $bs->instance();
            $obj['Activity']['namecod'] = "mioNamecodTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "activities", "namecod='mioNamecodTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "activities", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "activities", "namecod='mioNamecodTest'");
        }
    }

    public function testGet() {
        $autoIncrement = $this->addRecord();
        $data = array(
            'id_activity' => 1,
        );
        $post1 = $this->testAction('activity/get', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $responseData = json_decode($post1, true);
        $this->assertEquals($responseData['id'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        $data = array(
            'id_activity' => 9999999,
        );
        $post1 = $this->testAction('activity/get', array(
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
        $post2 = $this->testAction('activity/table', array(
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
        $bs = new ActivityBS();
        $sql = "SELECT COUNT(*) as num FROM activities";
        $num = $bs->queryCount($sql, "num");

        $autoIncrement = null;
        $dbo = null;
        if ($num == 1) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "activities");

            // insert
            $sql = "INSERT INTO activities (id,namecod,created) VALUES";
            $sql .= " (NULL, 'mioNamecodTest', CURRENT_TIMESTAMP)";
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
            $post1 = $this->testAction('activity/table', array(
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
            $search = "SELECT * FROM activities WHERE namecod='mioNamecodTest'";
            $data = $dbo->query($search);
            $result = $data[0]['activities'];
            $id = $result['id'];
            $this->assertEquals(!empty($data), true);
            $this->assertEquals($result['namecod'], 'mioNamecodTest');

            // delete
            $bs = new ActivityBS();
            $id = $bs->delete($id);

            // reset
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "activities", $autoIncrement);

            // verify reset
            MysqlUtilityTest::verifyDeleted($dbo, $this, "activities", "namecod='mioNamecodTest'");
        }
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "activities");

        // obj
        $bs = new ActivityBS();
        $obj = $bs->instance();
        $obj['Activity']['namecod'] = "mioNamecodTest";

        // save
        $data = array(
            'activity' => json_encode($obj['Activity']),
        );
        $post1 = $this->testAction('activity/save', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $this->assertEquals(!empty($post1), 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        // search
        $search = "SELECT * FROM activities WHERE namecod='mioNamecodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['activities'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['namecod'], 'mioNamecodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "activities", "namecod='mioNamecodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "activities", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "activities", "namecod='mioNamecodTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new ActivityBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Activity']['namecod'] = "AltramioNamecodTest";

        // edit
        $data = array(
            'id_activity' => $objNew['Activity']['id'],
            'activity' => json_encode($objNew['Activity']),
        );
        $post1 = $this->testAction('activity/edit', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $this->assertEquals(!empty($post1), 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        // test
        $bs = new ActivityBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Activity']['namecod'], 'AltramioNamecodTest');

        // reset
        $ui = new ActivityUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new ActivityBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Activity']['namecod'] == 'AltramioNamecodTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "activities");

        // insert
        $sql = "INSERT INTO activities (id,namecod,created) VALUES";
        $sql .= " (NULL, 'mioNamecodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM activities WHERE namecod='mioNamecodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['activities'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['namecod'], 'mioNamecodTest');

        // delete
        $data = array(
            'id_activity' => $id,
        );
        $post1 = $this->testAction('activity/delete', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $this->assertEquals(!empty($post1), 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "activities", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "activities", "namecod='mioNamecodTest'");
    }
}