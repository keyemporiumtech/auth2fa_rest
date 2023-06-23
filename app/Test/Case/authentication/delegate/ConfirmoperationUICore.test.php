<?php
App::uses("ConfirmoperationUI", "modules/authentication/delegate");
App::uses("ConfirmoperationBS", "modules/authentication/business");
App::uses("MysqlUtilityTest", "Test/utility");

class ConfirmoperationUICoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new ConfirmoperationBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "confirmoperations");
            $bs = new ConfirmoperationBS();
            $obj = $bs->instance();
            $obj['Confirmoperation']['codoperation'] = "mioCodoperation";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "confirmoperations", "codoperation='mioCodoperation'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "confirmoperations", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "confirmoperations", "codoperation='mioCodoperation'");
        }
    }

    public function testGet() {
        $autoIncrement = $this->addRecord();
        $ui = new ConfirmoperationUI();
        $obj = $ui->get(1);
        $this->assertEquals($obj['Confirmoperation']['id'], 1);
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
        $ui = new ConfirmoperationUI();
        $paginator = $ui->table($conditions);
        $this->assertEquals($paginator['list'][0]['Confirmoperation']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "confirmoperations");

        // obj
        $bs = new ConfirmoperationBS();
        $obj = $bs->instance();
        $obj['Confirmoperation']['codoperation'] = "mioCodoperation";

        // save
        $ui = new ConfirmoperationUI();
        $id = $ui->save($obj);

        // search
        $search = "SELECT * FROM confirmoperations WHERE codoperation='mioCodoperation'";
        $data = $dbo->query($search);
        $result = $data[0]['confirmoperations'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['codoperation'], 'mioCodoperation');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "confirmoperations", "codoperation='mioCodoperation'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "confirmoperations", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "confirmoperations", "codoperation='mioCodoperation'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new ConfirmoperationBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Confirmoperation']['codoperation'] = "OthermioCodoperation";

        // edit
        $ui = new ConfirmoperationUI();
        $id = $ui->edit($id, $objNew);

        // test
        $bs = new ConfirmoperationBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Confirmoperation']['codoperation'], 'OthermioCodoperation');

        // reset
        $ui = new ConfirmoperationUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new ConfirmoperationBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Confirmoperation']['codoperation'] == 'OthermioCodoperation', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "confirmoperations");

        // insert
        $sql = "INSERT INTO confirmoperations (id,codoperation,created) VALUES";
        $sql .= " (NULL, 'mioCodoperation', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM confirmoperations WHERE codoperation='mioCodoperation'";
        $data = $dbo->query($search);
        $result = $data[0]['confirmoperations'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['codoperation'], 'mioCodoperation');

        // delete
        $ui = new ConfirmoperationUI();
        $ui->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "confirmoperations", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "confirmoperations", "codoperation='mioCodoperation'");
    }
}