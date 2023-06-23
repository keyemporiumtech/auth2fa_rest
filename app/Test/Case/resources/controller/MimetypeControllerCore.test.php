<?php
App::uses("MimetypeBS", "modules/resources/business");
App::uses("Codes", "Config/system");
App::uses("MysqlUtilityTest", "Test/utility");

class MimetypeControllerCoreTest extends ControllerTestCase {

    public function addRecord() {
        $bs = new MimetypeBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "mimetypes");
            $bs = new MimetypeBS();
            $obj = $bs->instance();
            $obj['Mimetype']['value'] = "mioValueTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "mimetypes", "value='mioValueTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "mimetypes", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "mimetypes", "value='mioValueTest'");
        }
    }

    public function testGet() {
        $autoIncrement = $this->addRecord();
        $data = array(
            'id_mimetype' => 1,
        );
        $post1 = $this->testAction('mimetype/get', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $responseData = json_decode($post1, true);
        $this->assertEquals($responseData['id'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        $data = array(
            'id_mimetype' => 9999999,
        );
        $post1 = $this->testAction('mimetype/get', array(
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
        $post2 = $this->testAction('mimetype/table', array(
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
        $bs = new MimetypeBS();
        $sql = "SELECT COUNT(*) as num FROM mimetypes";
        $num = $bs->queryCount($sql, "num");

        $autoIncrement = null;
        $dbo = null;
        if ($num == 1) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "mimetypes");

            // insert
            $sql = "INSERT INTO mimetypes (id,value,created) VALUES";
            $sql .= " (NULL, 'mioValueTest', CURRENT_TIMESTAMP)";
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
            $post1 = $this->testAction('mimetype/table', array(
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
            $search = "SELECT * FROM mimetypes WHERE value='mioValueTest'";
            $data = $dbo->query($search);
            $result = $data[0]['mimetypes'];
            $id = $result['id'];
            $this->assertEquals(!empty($data), true);
            $this->assertEquals($result['value'], 'mioValueTest');

            // delete
            $bs = new MimetypeBS();
            $id = $bs->delete($id);

            // reset
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "mimetypes", $autoIncrement);

            // verify reset
            MysqlUtilityTest::verifyDeleted($dbo, $this, "mimetypes", "value='mioValueTest'");
        }
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "mimetypes");

        // obj
        $bs = new MimetypeBS();
        $obj = $bs->instance();
        $obj['Mimetype']['value'] = "mioValueTest";

        // save
        $data = array(
            'mimetype' => json_encode($obj['Mimetype']),
        );
        $post1 = $this->testAction('mimetype/save', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $this->assertEquals(!empty($post1), 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        // search
        $search = "SELECT * FROM mimetypes WHERE value='mioValueTest'";
        $data = $dbo->query($search);
        $result = $data[0]['mimetypes'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['value'], 'mioValueTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "mimetypes", "value='mioValueTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "mimetypes", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "mimetypes", "value='mioValueTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new MimetypeBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Mimetype']['value'] = "AltramioValueTest";

        // edit
        $data = array(
            'id_mimetype' => $objNew['Mimetype']['id'],
            'mimetype' => json_encode($objNew['Mimetype']),
        );
        $post1 = $this->testAction('mimetype/edit', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $this->assertEquals(!empty($post1), 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        // test
        $bs = new MimetypeBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Mimetype']['value'], 'AltramioValueTest');

        // reset
        $ui = new MimetypeUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new MimetypeBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Mimetype']['value'] == 'AltramioValueTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "mimetypes");

        // insert
        $sql = "INSERT INTO mimetypes (id,value,created) VALUES";
        $sql .= " (NULL, 'mioValueTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM mimetypes WHERE value='mioValueTest'";
        $data = $dbo->query($search);
        $result = $data[0]['mimetypes'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['value'], 'mioValueTest');

        // delete
        $data = array(
            'id_mimetype' => $id,
        );
        $post1 = $this->testAction('mimetype/delete', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $this->assertEquals(!empty($post1), 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "mimetypes", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "mimetypes", "value='mioValueTest'");
    }
}