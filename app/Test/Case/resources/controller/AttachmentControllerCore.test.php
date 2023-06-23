<?php
App::uses("AttachmentBS", "modules/resources/business");
App::uses("Codes", "Config/system");
App::uses("MysqlUtilityTest", "Test/utility");

class AttachmentControllerCoreTest extends ControllerTestCase {

    public function addRecord() {
        $bs = new AttachmentBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "attachments");
            $bs = new AttachmentBS();
            $obj = $bs->instance();
            $obj['Attachment']['cod'] = "mioCodTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "attachments", "cod='mioCodTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "attachments", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "attachments", "cod='mioCodTest'");
        }
    }

    public function testGet() {
        $autoIncrement = $this->addRecord();
        $data = array(
            'id_attachment' => 1,
        );
        $post1 = $this->testAction('attachment/get', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $responseData = json_decode($post1, true);
        $this->assertEquals($responseData['id'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        $data = array(
            'id_attachment' => 9999999,
        );
        $post1 = $this->testAction('attachment/get', array(
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
        $post2 = $this->testAction('attachment/table', array(
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
        $bs = new AttachmentBS();
        $sql = "SELECT COUNT(*) as num FROM attachments";
        $num = $bs->queryCount($sql, "num");

        $autoIncrement = null;
        $dbo = null;
        if ($num == 1) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "attachments");

            // insert
            $sql = "INSERT INTO attachments (id,cod,created) VALUES";
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
            $post1 = $this->testAction('attachment/table', array(
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
            $search = "SELECT * FROM attachments WHERE cod='mioCodTest'";
            $data = $dbo->query($search);
            $result = $data[0]['attachments'];
            $id = $result['id'];
            $this->assertEquals(!empty($data), true);
            $this->assertEquals($result['cod'], 'mioCodTest');

            // delete
            $bs = new AttachmentBS();
            $id = $bs->delete($id);

            // reset
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "attachments", $autoIncrement);

            // verify reset
            MysqlUtilityTest::verifyDeleted($dbo, $this, "attachments", "cod='mioCodTest'");
        }
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "attachments");

        // obj
        $bs = new AttachmentBS();
        $obj = $bs->instance();
        $obj['Attachment']['cod'] = "mioCodTest";

        // save
        $data = array(
            'attachment' => json_encode($obj['Attachment']),
        );
        $post1 = $this->testAction('attachment/save', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $this->assertEquals(!empty($post1), 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        // search
        $search = "SELECT * FROM attachments WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['attachments'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "attachments", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "attachments", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "attachments", "cod='mioCodTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new AttachmentBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Attachment']['cod'] = "AltramioCodTest";

        // edit
        $data = array(
            'id_attachment' => $objNew['Attachment']['id'],
            'attachment' => json_encode($objNew['Attachment']),
        );
        $post1 = $this->testAction('attachment/edit', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $this->assertEquals(!empty($post1), 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        // test
        $bs = new AttachmentBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Attachment']['cod'], 'AltramioCodTest');

        // reset
        $ui = new AttachmentUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new AttachmentBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Attachment']['cod'] == 'AltramioCodTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "attachments");

        // insert
        $sql = "INSERT INTO attachments (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM attachments WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['attachments'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $data = array(
            'id_attachment' => $id,
        );
        $post1 = $this->testAction('attachment/delete', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $this->assertEquals(!empty($post1), 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "attachments", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "attachments", "cod='mioCodTest'");
    }
}