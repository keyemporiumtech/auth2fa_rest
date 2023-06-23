<?php
App::uses("MailerUI", "modules/communication/delegate");
App::uses("MailerBS", "modules/communication/business");
App::uses("MysqlUtilityTest", "Test/utility");

class MailerUICoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new MailerBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "mailers");
            $bs = new MailerBS();
            $obj = $bs->instance();
            $obj['Mailer']['cod'] = "mioCodTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "mailers", "cod='mioCodTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "mailers", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "mailers", "cod='mioCodTest'");
        }
    }

    public function testGet() {
        $autoIncrement = $this->addRecord();
        $ui = new MailerUI();
        $obj = $ui->get(1);
        $this->assertEquals($obj['Mailer']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testTable() {
        $autoIncrement = $this->addRecord();
        $condition = new DBCondition();
        $condition->key = "id";
        $condition->value = 1;
        $conditions = array(
            $condition,
        );
        $ui = new MailerUI();
        $paginator = $ui->table($conditions);
        $this->assertEquals($paginator['list'][0]['Mailer']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "mailers");

        // obj
        $bs = new MailerBS();
        $obj = $bs->instance();
        $obj['Mailer']['cod'] = "mioCodTest";

        // save
        $ui = new MailerUI();
        $id = $ui->save($obj);

        // search
        $search = "SELECT * FROM mailers WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['mailers'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "mailers", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "mailers", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "mailers", "cod='mioCodTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new MailerBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Mailer']['cod'] = "OthermioCodTest";

        // edit
        $ui = new MailerUI();
        $id = $ui->edit($id, $objNew);

        // test
        $bs = new MailerBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Mailer']['cod'], 'OthermioCodTest');

        // reset
        $ui = new MailerUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new MailerBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Mailer']['cod'] == 'OthermioCodTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "mailers");

        // insert
        $sql = "INSERT INTO mailers (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM mailers WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['mailers'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $ui = new MailerUI();
        $ui->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "mailers", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "mailers", "cod='mioCodTest'");
    }
}