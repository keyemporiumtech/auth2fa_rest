<?php
App::uses("MailcidBS", "modules/communication/business");
App::uses("Codes", "Config/system");
App::uses("MysqlUtilityTest", "Test/utility");

class MailcidControllerCoreTest extends ControllerTestCase {

    public function addRecord() {
        $bs = new MailcidBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "mailcids");
            $bs = new MailcidBS();
            $obj = $bs->instance();
            $obj['Mailcid']['cid'] = "mioCidTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "mailcids", "cid='mioCidTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "mailcids", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "mailcids", "cid='mioCidTest'");
        }
    }

    public function testGet() {
        $autoIncrement = $this->addRecord();
        $data = array(
            'id_mailcid' => 1,
        );
        $post1 = $this->testAction('mailcid/get', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $responseData = json_decode($post1, true);
        $this->assertEquals($responseData['id'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        $data = array(
            'id_mailcid' => 9999999,
        );
        $post1 = $this->testAction('mailcid/get', array(
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
        $post2 = $this->testAction('mailcid/table', array(
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
        $bs = new MailcidBS();
        $sql = "SELECT COUNT(*) as num FROM mailcids";
        $num = $bs->queryCount($sql, "num");

        $autoIncrement = null;
        $dbo = null;
        if ($num == 1) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "mailcids");

            // insert
            $sql = "INSERT INTO mailcids (id,cid,created) VALUES";
            $sql .= " (NULL, 'mioCidTest', CURRENT_TIMESTAMP)";
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
            $post1 = $this->testAction('mailcid/table', array(
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
            $search = "SELECT * FROM mailcids WHERE cid='mioCidTest'";
            $data = $dbo->query($search);
            $result = $data[0]['mailcids'];
            $id = $result['id'];
            $this->assertEquals(!empty($data), true);
            $this->assertEquals($result['cid'], 'mioCidTest');

            // delete
            $bs = new MailcidBS();
            $id = $bs->delete($id);

            // reset
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "mailcids", $autoIncrement);

            // verify reset
            MysqlUtilityTest::verifyDeleted($dbo, $this, "mailcids", "cid='mioCidTest'");
        }
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "mailcids");

        // obj
        $bs = new MailcidBS();
        $obj = $bs->instance();
        $obj['Mailcid']['cid'] = "mioCidTest";

        // save
        $data = array(
            'mailcid' => json_encode($obj['Mailcid']),
        );
        $post1 = $this->testAction('mailcid/save', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $this->assertEquals(!empty($post1), 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        // search
        $search = "SELECT * FROM mailcids WHERE cid='mioCidTest'";
        $data = $dbo->query($search);
        $result = $data[0]['mailcids'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cid'], 'mioCidTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "mailcids", "cid='mioCidTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "mailcids", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "mailcids", "cid='mioCidTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new MailcidBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Mailcid']['cid'] = "AltramioCidTest";

        // edit
        $data = array(
            'id_mailcid' => $objNew['Mailcid']['id'],
            'mailcid' => json_encode($objNew['Mailcid']),
        );
        $post1 = $this->testAction('mailcid/edit', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $this->assertEquals(!empty($post1), 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        // test
        $bs = new MailcidBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Mailcid']['cid'], 'AltramioCidTest');

        // reset
        $ui = new MailcidUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new MailcidBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Mailcid']['cid'] == 'AltramioCidTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "mailcids");

        // insert
        $sql = "INSERT INTO mailcids (id,cid,created) VALUES";
        $sql .= " (NULL, 'mioCidTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM mailcids WHERE cid='mioCidTest'";
        $data = $dbo->query($search);
        $result = $data[0]['mailcids'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cid'], 'mioCidTest');

        // delete
        $data = array(
            'id_mailcid' => $id,
        );
        $post1 = $this->testAction('mailcid/delete', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $this->assertEquals(!empty($post1), 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "mailcids", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "mailcids", "cid='mioCidTest'");
    }
}