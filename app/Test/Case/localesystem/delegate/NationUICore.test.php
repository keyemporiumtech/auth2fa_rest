<?php
App::uses("NationUI", "modules/localesystem/delegate");
App::uses("NationBS", "modules/localesystem/business");
App::uses("MysqlUtilityTest", "Test/utility");

class NationUICoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new NationBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "nations");
            $bs = new NationBS();
            $obj = $bs->instance();
            $obj['Nation']['cod'] = "mioCodTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "nations", "cod='mioCodTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "nations", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "nations", "cod='mioCodTest'");
        }
    }

    public function testGet() {
        $autoIncrement = $this->addRecord();
        $ui = new NationUI();
        $obj = $ui->get(1);
        $this->assertEquals($obj['Nation']['id'], 1);
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
        $ui = new NationUI();
        $paginator = $ui->table($conditions);
        $this->assertEquals($paginator['list'][0]['Nation']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "nations");

        // obj
        $bs = new NationBS();
        $obj = $bs->instance();
        $obj['Nation']['cod'] = "mioCodTest";

        // save
        $ui = new NationUI();
        $id = $ui->save($obj);

        // search
        $search = "SELECT * FROM nations WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['nations'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "nations", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "nations", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "nations", "cod='mioCodTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new NationBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Nation']['cod'] = "OthermioCodTest";

        // edit
        $ui = new NationUI();
        $id = $ui->edit($id, $objNew);

        // test
        $bs = new NationBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Nation']['cod'], 'OthermioCodTest');

        // reset
        $ui = new NationUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new NationBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Nation']['cod'] == 'OthermioCodTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "nations");

        // insert
        $sql = "INSERT INTO nations (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM nations WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['nations'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $ui = new NationUI();
        $ui->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "nations", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "nations", "cod='mioCodTest'");
    }
}