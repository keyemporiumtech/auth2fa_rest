<?php
App::uses("UserreportUI", "modules/authentication/delegate");
App::uses("UserreportBS", "modules/authentication/business");
App::uses("MysqlUtilityTest", "Test/utility");

class UserreportUICoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new UserreportBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "userreports");
            $bs = new UserreportBS();
            $obj = $bs->instance();
            $obj['Userreport']['codoperation'] = "mioCodoperationTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "userreports", "codoperation='mioCodoperationTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "userreports", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "userreports", "codoperation='mioCodoperationTest'");
        }
    }

    public function testGet() {
        $autoIncrement = $this->addRecord();
        $ui = new UserreportUI();
        $obj = $ui->get(1);
        $this->assertEquals($obj['Userreport']['id'], 1);
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
        $ui = new UserreportUI();
        $paginator = $ui->table($conditions);
        $this->assertEquals($paginator['list'][0]['Userreport']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "userreports");

        // obj
        $bs = new UserreportBS();
        $obj = $bs->instance();
        $obj['Userreport']['codoperation'] = "mioCodoperationTest";

        // save
        $ui = new UserreportUI();
        $id = $ui->save($obj);

        // search
        $search = "SELECT * FROM userreports WHERE codoperation='mioCodoperationTest'";
        $data = $dbo->query($search);
        $result = $data[0]['userreports'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['codoperation'], 'mioCodoperationTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "userreports", "codoperation='mioCodoperationTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "userreports", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "userreports", "codoperation='mioCodoperationTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new UserreportBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Userreport']['codoperation'] = "OthermioCodoperationTest";

        // edit
        $ui = new UserreportUI();
        $id = $ui->edit($id, $objNew);

        // test
        $bs = new UserreportBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Userreport']['codoperation'], 'OthermioCodoperationTest');

        // reset
        $ui = new UserreportUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new UserreportBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Userreport']['codoperation'] == 'OthermioCodoperationTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "userreports");

        // insert
        $sql = "INSERT INTO userreports (id,codoperation,created) VALUES";
        $sql .= " (NULL, 'mioCodoperationTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM userreports WHERE codoperation='mioCodoperationTest'";
        $data = $dbo->query($search);
        $result = $data[0]['userreports'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['codoperation'], 'mioCodoperationTest');

        // delete
        $ui = new UserreportUI();
        $ui->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "userreports", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "userreports", "codoperation='mioCodoperationTest'");
    }
}