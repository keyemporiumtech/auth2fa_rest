<?php
App::uses("UserUI", "modules/authentication/delegate");
App::uses("UserBS", "modules/authentication/business");
App::uses("MysqlUtilityTest", "Test/utility");

class UserUICoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new UserBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "users");
            $bs = new UserBS();
            $obj = $bs->instance();
            $obj['User']['username'] = "mioUsernameTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "users", "username='mioUsernameTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "users", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "users", "username='mioUsernameTest'");
        }
    }

    public function testGet() {
        $autoIncrement = $this->addRecord();
        $ui = new UserUI();
        $obj = $ui->get(1);
        $this->assertEquals($obj['User']['id'], 1);
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
        $ui = new UserUI();
        $paginator = $ui->table($conditions);
        $this->assertEquals($paginator['list'][0]['User']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "users");

        // obj
        $bs = new UserBS();
        $obj = $bs->instance();
        $obj['User']['username'] = "mioUsernameTest";

        // save
        $ui = new UserUI();
        $id = $ui->save($obj);

        // search
        $search = "SELECT * FROM users WHERE username='mioUsernameTest'";
        $data = $dbo->query($search);
        $result = $data[0]['users'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['username'], 'mioUsernameTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "users", "username='mioUsernameTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "users", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "users", "username='mioUsernameTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new UserBS();
        $bs->addPropertyDao("avoidEmptyPassword", true);
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['User']['username'] = "OthermioUsernameTest";

        // edit
        $ui = new UserUI();
        $id = $ui->edit($id, $objNew);

        // test
        $bs = new UserBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['User']['username'], 'OthermioUsernameTest');

        // reset
        $ui = new UserUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new UserBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['User']['username'] == 'OthermioUsernameTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "users");

        // insert
        $sql = "INSERT INTO users (id,username,created) VALUES";
        $sql .= " (NULL, 'mioUsernameTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM users WHERE username='mioUsernameTest'";
        $data = $dbo->query($search);
        $result = $data[0]['users'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['username'], 'mioUsernameTest');

        // delete
        $ui = new UserUI();
        $ui->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "users", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "users", "username='mioUsernameTest'");
    }
}