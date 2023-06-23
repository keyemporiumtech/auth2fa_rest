<?php
App::uses("PhoneBS", "modules/communication/business");
App::uses("Codes", "Config/system");
App::uses("MysqlUtilityTest", "Test/utility");

class PhoneControllerCoreTest extends ControllerTestCase {

    public function addRecord() {
        $bs = new PhoneBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "phones");
            $bs = new PhoneBS();
            $obj = $bs->instance();
            $obj['Phone']['sendername'] = "mioSendernameTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "phones", "sendername='mioSendernameTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "phones", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "phones", "sendername='mioSendernameTest'");
        }
    }

    public function testGet() {
        $autoIncrement = $this->addRecord();
        $data = array(
            'id_phone' => 1,
        );
        $post1 = $this->testAction('phone/get', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $responseData = json_decode($post1, true);
        $this->assertEquals($responseData['id'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        $data = array(
            'id_phone' => 9999999,
        );
        $post1 = $this->testAction('phone/get', array(
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
        $post2 = $this->testAction('phone/table', array(
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
        $bs = new PhoneBS();
        $sql = "SELECT COUNT(*) as num FROM phones";
        $num = $bs->queryCount($sql, "num");

        $autoIncrement = null;
        $dbo = null;
        if ($num == 1) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "phones");

            // insert
            $sql = "INSERT INTO phones (id,sendername,created) VALUES";
            $sql .= " (NULL, 'mioSendernameTest', CURRENT_TIMESTAMP)";
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
            $post1 = $this->testAction('phone/table', array(
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
            $search = "SELECT * FROM phones WHERE sendername='mioSendernameTest'";
            $data = $dbo->query($search);
            $result = $data[0]['phones'];
            $id = $result['id'];
            $this->assertEquals(!empty($data), true);
            $this->assertEquals($result['sendername'], 'mioSendernameTest');

            // delete
            $bs = new PhoneBS();
            $id = $bs->delete($id);

            // reset
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "phones", $autoIncrement);

            // verify reset
            MysqlUtilityTest::verifyDeleted($dbo, $this, "phones", "sendername='mioSendernameTest'");
        }
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "phones");

        // obj
        $bs = new PhoneBS();
        $obj = $bs->instance();
        $obj['Phone']['sendername'] = "mioSendernameTest";

        // save
        $data = array(
            'phone' => json_encode($obj['Phone']),
        );
        $post1 = $this->testAction('phone/save', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $this->assertEquals(!empty($post1), 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        // search
        $search = "SELECT * FROM phones WHERE sendername='mioSendernameTest'";
        $data = $dbo->query($search);
        $result = $data[0]['phones'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['sendername'], 'mioSendernameTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "phones", "sendername='mioSendernameTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "phones", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "phones", "sendername='mioSendernameTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new PhoneBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Phone']['sendername'] = "AltramioSendernameTest";

        // edit
        $data = array(
            'id_phone' => $objNew['Phone']['id'],
            'phone' => json_encode($objNew['Phone']),
        );
        $post1 = $this->testAction('phone/edit', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $this->assertEquals(!empty($post1), 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        // test
        $bs = new PhoneBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Phone']['sendername'], 'AltramioSendernameTest');

        // reset
        $ui = new PhoneUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new PhoneBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Phone']['sendername'] == 'AltramioSendernameTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "phones");

        // insert
        $sql = "INSERT INTO phones (id,sendername,created) VALUES";
        $sql .= " (NULL, 'mioSendernameTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM phones WHERE sendername='mioSendernameTest'";
        $data = $dbo->query($search);
        $result = $data[0]['phones'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['sendername'], 'mioSendernameTest');

        // delete
        $data = array(
            'id_phone' => $id,
        );
        $post1 = $this->testAction('phone/delete', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $this->assertEquals(!empty($post1), 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "phones", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "phones", "sendername='mioSendernameTest'");
    }
}