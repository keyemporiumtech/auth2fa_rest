<?php
App::uses("PhonereceiverBS", "modules/communication/business");
App::uses("Codes", "Config/system");
App::uses("MysqlUtilityTest", "Test/utility");

class PhonereceiverControllerCoreTest extends ControllerTestCase {

    public function addRecord() {
        $bs = new PhonereceiverBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "phonereceivers");
            $bs = new PhonereceiverBS();
            $obj = $bs->instance();
            $obj['Phonereceiver']['receivername'] = "mioReceivernameTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "phonereceivers", "receivername='mioReceivernameTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "phonereceivers", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "phonereceivers", "receivername='mioReceivernameTest'");
        }
    }

    public function testGet() {
        $autoIncrement = $this->addRecord();
        $data = array(
            'id_phonereceiver' => 1,
        );
        $post1 = $this->testAction('phonereceiver/get', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $responseData = json_decode($post1, true);
        $this->assertEquals($responseData['id'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        $data = array(
            'id_phonereceiver' => 9999999,
        );
        $post1 = $this->testAction('phonereceiver/get', array(
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
        $post2 = $this->testAction('phonereceiver/table', array(
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
        $bs = new PhonereceiverBS();
        $sql = "SELECT COUNT(*) as num FROM phonereceivers";
        $num = $bs->queryCount($sql, "num");

        $autoIncrement = null;
        $dbo = null;
        if ($num == 1) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "phonereceivers");

            // insert
            $sql = "INSERT INTO phonereceivers (id,receivername,created) VALUES";
            $sql .= " (NULL, 'mioReceivernameTest', CURRENT_TIMESTAMP)";
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
            $post1 = $this->testAction('phonereceiver/table', array(
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
            $search = "SELECT * FROM phonereceivers WHERE receivername='mioReceivernameTest'";
            $data = $dbo->query($search);
            $result = $data[0]['phonereceivers'];
            $id = $result['id'];
            $this->assertEquals(!empty($data), true);
            $this->assertEquals($result['receivername'], 'mioReceivernameTest');

            // delete
            $bs = new PhonereceiverBS();
            $id = $bs->delete($id);

            // reset
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "phonereceivers", $autoIncrement);

            // verify reset
            MysqlUtilityTest::verifyDeleted($dbo, $this, "phonereceivers", "receivername='mioReceivernameTest'");
        }
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "phonereceivers");

        // obj
        $bs = new PhonereceiverBS();
        $obj = $bs->instance();
        $obj['Phonereceiver']['receivername'] = "mioReceivernameTest";

        // save
        $data = array(
            'phonereceiver' => json_encode($obj['Phonereceiver']),
        );
        $post1 = $this->testAction('phonereceiver/save', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $this->assertEquals(!empty($post1), 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        // search
        $search = "SELECT * FROM phonereceivers WHERE receivername='mioReceivernameTest'";
        $data = $dbo->query($search);
        $result = $data[0]['phonereceivers'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['receivername'], 'mioReceivernameTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "phonereceivers", "receivername='mioReceivernameTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "phonereceivers", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "phonereceivers", "receivername='mioReceivernameTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new PhonereceiverBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Phonereceiver']['receivername'] = "AltramioReceivernameTest";

        // edit
        $data = array(
            'id_phonereceiver' => $objNew['Phonereceiver']['id'],
            'phonereceiver' => json_encode($objNew['Phonereceiver']),
        );
        $post1 = $this->testAction('phonereceiver/edit', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $this->assertEquals(!empty($post1), 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        // test
        $bs = new PhonereceiverBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Phonereceiver']['receivername'], 'AltramioReceivernameTest');

        // reset
        $ui = new PhonereceiverUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new PhonereceiverBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Phonereceiver']['receivername'] == 'AltramioReceivernameTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "phonereceivers");

        // insert
        $sql = "INSERT INTO phonereceivers (id,receivername,created) VALUES";
        $sql .= " (NULL, 'mioReceivernameTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM phonereceivers WHERE receivername='mioReceivernameTest'";
        $data = $dbo->query($search);
        $result = $data[0]['phonereceivers'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['receivername'], 'mioReceivernameTest');

        // delete
        $data = array(
            'id_phonereceiver' => $id,
        );
        $post1 = $this->testAction('phonereceiver/delete', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $this->assertEquals(!empty($post1), 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "phonereceivers", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "phonereceivers", "receivername='mioReceivernameTest'");
    }
}