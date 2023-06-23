<?php
App::uses("ProfessionroleUI", "modules/work_cv/delegate");
App::uses("ProfessionroleBS", "modules/work_cv/business");
App::uses("MysqlUtilityTest", "Test/utility");

class ProfessionroleUICoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new ProfessionroleBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "professionroles");
            $bs = new ProfessionroleBS();
            $obj = $bs->instance();
            $obj['Professionrole']['cod'] = "mioCodTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "professionroles", "cod='mioCodTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "professionroles", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "professionroles", "cod='mioCodTest'");
        }
    }

    public function testGet() {
        $autoIncrement = $this->addRecord();
        $ui = new ProfessionroleUI();
        $obj = $ui->get(1);
        $this->assertEquals($obj['Professionrole']['id'], 1);
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
        $ui = new ProfessionroleUI();
        $paginator = $ui->table($conditions);
        $this->assertEquals($paginator['list'][0]['Professionrole']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "professionroles");

        // obj
        $bs = new ProfessionroleBS();
        $obj = $bs->instance();
        $obj['Professionrole']['cod'] = "mioCodTest";

        // save
        $ui = new ProfessionroleUI();
        $id = $ui->save($obj);

        // search
        $search = "SELECT * FROM professionroles WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['professionroles'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "professionroles", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "professionroles", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "professionroles", "cod='mioCodTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new ProfessionroleBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Professionrole']['cod'] = "OthermioCodTest";

        // edit
        $ui = new ProfessionroleUI();
        $id = $ui->edit($id, $objNew);

        // test
        $bs = new ProfessionroleBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Professionrole']['cod'], 'OthermioCodTest');

        // reset
        $ui = new ProfessionroleUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new ProfessionroleBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Professionrole']['cod'] == 'OthermioCodTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "professionroles");

        // insert
        $sql = "INSERT INTO professionroles (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM professionroles WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['professionroles'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $ui = new ProfessionroleUI();
        $ui->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "professionroles", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "professionroles", "cod='mioCodTest'");
    }
}