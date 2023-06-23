<?php
App::uses("MailreceiverBS", "modules/communication/business");
App::uses("Codes", "Config/system");
App::uses("MysqlUtilityTest", "Test/utility");

class MailreceiverControllerCoreTest extends ControllerTestCase {

    public function addRecord() {
        $bs = new MailreceiverBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "mailreceivers");
            $bs = new MailreceiverBS();
            $obj = $bs->instance();
            $obj['Mailreceiver']['receivername'] = "mioReceivernameTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "mailreceivers", "receivername='mioReceivernameTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "mailreceivers", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "mailreceivers", "receivername='mioReceivernameTest'");
        }
    }

    public function testGet() {
        $autoIncrement = $this->addRecord();
        $data = array(
            'id_mailreceiver' => 1,
        );
        $post1 = $this->testAction('mailreceiver/get', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $responseData = json_decode($post1, true);
        $this->assertEquals($responseData['id'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        $data = array(
            'id_mailreceiver' => 9999999,
        );
        $post1 = $this->testAction('mailreceiver/get', array(
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
        $post2 = $this->testAction('mailreceiver/table', array(
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
        $bs = new MailreceiverBS();
        $sql = "SELECT COUNT(*) as num FROM mailreceivers";
        $num = $bs->queryCount($sql, "num");

        $autoIncrement = null;
        $dbo = null;
        if ($num == 1) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "mailreceivers");

            // insert
            $sql = "INSERT INTO mailreceivers (id,receivername,created) VALUES";
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
            $post1 = $this->testAction('mailreceiver/table', array(
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
            $search = "SELECT * FROM mailreceivers WHERE receivername='mioReceivernameTest'";
            $data = $dbo->query($search);
            $result = $data[0]['mailreceivers'];
            $id = $result['id'];
            $this->assertEquals(!empty($data), true);
            $this->assertEquals($result['receivername'], 'mioReceivernameTest');

            // delete
            $bs = new MailreceiverBS();
            $id = $bs->delete($id);

            // reset
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "mailreceivers", $autoIncrement);

            // verify reset
            MysqlUtilityTest::verifyDeleted($dbo, $this, "mailreceivers", "receivername='mioReceivernameTest'");
        }
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "mailreceivers");

        // obj
        $bs = new MailreceiverBS();
        $obj = $bs->instance();
        $obj['Mailreceiver']['receivername'] = "mioReceivernameTest";

        // save
        $data = array(
            'mailreceiver' => json_encode($obj['Mailreceiver']),
        );
        $post1 = $this->testAction('mailreceiver/save', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $this->assertEquals(!empty($post1), 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        // search
        $search = "SELECT * FROM mailreceivers WHERE receivername='mioReceivernameTest'";
        $data = $dbo->query($search);
        $result = $data[0]['mailreceivers'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['receivername'], 'mioReceivernameTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "mailreceivers", "receivername='mioReceivernameTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "mailreceivers", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "mailreceivers", "receivername='mioReceivernameTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new MailreceiverBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Mailreceiver']['receivername'] = "AltramioReceivernameTest";

        // edit
        $data = array(
            'id_mailreceiver' => $objNew['Mailreceiver']['id'],
            'mailreceiver' => json_encode($objNew['Mailreceiver']),
        );
        $post1 = $this->testAction('mailreceiver/edit', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $this->assertEquals(!empty($post1), 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        // test
        $bs = new MailreceiverBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Mailreceiver']['receivername'], 'AltramioReceivernameTest');

        // reset
        $ui = new MailreceiverUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new MailreceiverBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Mailreceiver']['receivername'] == 'AltramioReceivernameTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "mailreceivers");

        // insert
        $sql = "INSERT INTO mailreceivers (id,receivername,created) VALUES";
        $sql .= " (NULL, 'mioReceivernameTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM mailreceivers WHERE receivername='mioReceivernameTest'";
        $data = $dbo->query($search);
        $result = $data[0]['mailreceivers'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['receivername'], 'mioReceivernameTest');

        // delete
        $data = array(
            'id_mailreceiver' => $id,
        );
        $post1 = $this->testAction('mailreceiver/delete', array(
            'data' => $data,
            'return' => 'view',
            'method' => 'POST',
        ));

        $this->assertEquals(!empty($post1), 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['statuscod'], 1);
        $this->assertEquals($this->controller->response->header('_headers', '')['responsecod'], 200);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "mailreceivers", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "mailreceivers", "receivername='mioReceivernameTest'");
    }
}