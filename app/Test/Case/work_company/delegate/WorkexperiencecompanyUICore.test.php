<?php
App::uses("WorkexperiencecompanyUI", "modules/work_company/delegate");
App::uses("WorkexperiencecompanyBS", "modules/work_company/business");
App::uses("MysqlUtilityTest", "Test/utility");

class WorkexperiencecompanyUICoreTest extends CakeTestCase {

    public function addRecord() {
        $bs = new WorkexperiencecompanyBS();
        $num = $bs->count();
        if ($num == 0) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "workexperiencecompanies");
            $bs = new WorkexperiencecompanyBS();
            $obj = $bs->instance();
            $obj['Workexperiencecompany']['cod'] = "mioCodTest";
            $id = $bs->save($obj);
            return $autoIncrement;
        }
        return null;
    }

    public function removeRecord($autoIncrement) {
        if (!empty($autoIncrement)) {
            /** @var \Cake\Model\Datasource\DboSource */
            $dbo = ConnectionManager::getDataSource("default");
            MysqlUtilityTest::deleteLast($dbo, "workexperiencecompanies", "cod='mioCodTest'");
            MysqlUtilityTest::resetAutoIncrement($dbo, $this, "workexperiencecompanies", $autoIncrement);
            MysqlUtilityTest::verifyDeleted($dbo, $this, "workexperiencecompanies", "cod='mioCodTest'");
        }
    }

    public function testGet() {
        $autoIncrement = $this->addRecord();
        $ui = new WorkexperiencecompanyUI();
        $obj = $ui->get(1);
        $this->assertEquals($obj['Workexperiencecompany']['id'], 1);
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
        $ui = new WorkexperiencecompanyUI();
        $paginator = $ui->table($conditions);
        $this->assertEquals($paginator['list'][0]['Workexperiencecompany']['id'], 1);
        $this->removeRecord($autoIncrement);
    }

    public function testSave() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "workexperiencecompanies");

        // obj
        $bs = new WorkexperiencecompanyBS();
        $obj = $bs->instance();
        $obj['Workexperiencecompany']['cod'] = "mioCodTest";

        // save
        $ui = new WorkexperiencecompanyUI();
        $id = $ui->save($obj);

        // search
        $search = "SELECT * FROM workexperiencecompanies WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['workexperiencecompanies'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        MysqlUtilityTest::deleteLast($dbo, "workexperiencecompanies", "cod='mioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "workexperiencecompanies", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "workexperiencecompanies", "cod='mioCodTest'");
    }

    public function testEdit() {
        $autoIncrement = $this->addRecord();

        // obj
        $id = 1;
        $bs = new WorkexperiencecompanyBS();
        $obj = $bs->unique($id);

        $objNew = $obj;
        $objNew['Workexperiencecompany']['cod'] = "OthermioCodTest";

        // edit
        $ui = new WorkexperiencecompanyUI();
        $id = $ui->edit($id, $objNew);

        // test
        $bs = new WorkexperiencecompanyBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Workexperiencecompany']['cod'], 'OthermioCodTest');

        // reset
        $ui = new WorkexperiencecompanyUI();
        $id = $ui->edit($id, $obj);

        // test
        $bs = new WorkexperiencecompanyBS();
        $search = $bs->unique(1);
        $this->assertEquals(!empty($search), true);
        $this->assertEquals($search['Workexperiencecompany']['cod'] == 'OthermioCodTest', false);
        $this->removeRecord($autoIncrement);
    }

    public function testDelete() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "workexperiencecompanies");

        // insert
        $sql = "INSERT INTO workexperiencecompanies (id,cod,created) VALUES";
        $sql .= " (NULL, 'mioCodTest', CURRENT_TIMESTAMP)";
        $data = $dbo->query($sql);

        // search
        $search = "SELECT * FROM workexperiencecompanies WHERE cod='mioCodTest'";
        $data = $dbo->query($search);
        $result = $data[0]['workexperiencecompanies'];
        $id = $result['id'];
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($result['cod'], 'mioCodTest');

        // delete
        $ui = new WorkexperiencecompanyUI();
        $ui->delete($id);

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "workexperiencecompanies", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "workexperiencecompanies", "cod='mioCodTest'");
    }
}