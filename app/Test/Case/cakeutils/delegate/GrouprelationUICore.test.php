<?php
App::uses("GrouprelationUI", "modules/cakeutils/delegate");
App::uses("GrouprelationBS", "modules/cakeutils/business");
App::uses("MysqlUtilityTest", "Test/utility");

class GrouprelationUICoreTest extends CakeTestCase {

    function addRecord() {
        $bs = new GrouprelationBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "grouprelations");
            $bs = new GrouprelationBS();
            $obj = $bs->instance();
            $obj['Grouprelation']['cod'] = "mioCod";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "grouprelations", "cod='mioCod'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "grouprelations", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "grouprelations", "cod='mioCod'");
        }
    }

    function testGet() {
        $autoIncrement = $this->addRecord();
        $ui = new GrouprelationUI();
        $obj = $ui->get(1);
        $this->assertEquals($obj['Grouprelation']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    function testTable() {
        $autoIncrement = $this->addRecord();
        $condition = new DBCondition();
        $condition->key = "id";
        $condition->value = 1;
        $conditions = array(
            $condition,
        );
        $ui = new GrouprelationUI();
        $paginator = $ui->table($conditions);
        $this->assertEquals($paginator['list'][0]['Grouprelation']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "grouprelations");

        // obj
        $bs = new GrouprelationBS();
        $obj = $bs->instance();
        $obj['Grouprelation']['cod'] = "mioCod";

        // save
        $ui = new GrouprelationUI();
        $id = $ui->save($obj);

        // search
        $search = "SELECT * FROM grouprelations WHERE cod='mioCod'";
        $data = $dbo->query($search);
        $result = $data[0]['grouprelations'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCod');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "grouprelations", "cod='mioCod'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "grouprelations", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "grouprelations", "cod='mioCod'");
    }

    function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new GrouprelationBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Grouprelation']['cod'] = "OthermioCod";

        // edit
        $ui = new GrouprelationUI();
        $id = $ui->edit($id, $objNew);

        // test
        $bs = new GrouprelationBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Grouprelation']['cod'], 'OthermioCod');

        // reset
        $ui = new GrouprelationUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new GrouprelationBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Grouprelation']['cod'] == 'OthermioCod', false);
        $this->removeRecord($autoIncrement);
    }

    function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "grouprelations");

        // insert
        $sql = "INSERT INTO grouprelations (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCod', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM grouprelations WHERE cod='mioCod'";
        $data = $dbo->query($search);
        $result = $data[0]['grouprelations'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCod');

        // delete
        $ui = new GrouprelationUI();
        $ui->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "grouprelations", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "grouprelations", "cod='mioCod'");
    }
}