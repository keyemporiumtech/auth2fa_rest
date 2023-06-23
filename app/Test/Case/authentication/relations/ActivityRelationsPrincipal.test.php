<?php
App::uses("MysqlUtilityTest", "Test/utility");
App::uses("ActivityaddressUI", "modules/authentication/delegate");
App::uses("ActivityaddressBS", "modules/authentication/business");
App::uses("ActivityreferenceUI", "modules/authentication/delegate");
App::uses("ActivityreferenceBS", "modules/authentication/business");
App::uses("ActivityattachmentUI", "modules/authentication/delegate");
App::uses("ActivityattachmentBS", "modules/authentication/business");
App::uses("AddressBS", "modules/localesystem/business");
App::uses("ContactreferenceBS", "modules/authentication/business");
App::uses("AttachmentBS", "modules/resources/business");

class ActivityRelationsPrincipalTest extends CakeTestCase {

    public function testActivityaddressPrincipal() {
        $ui = new ActivityaddressUI();
        $principal = $ui->getPrincipal(null, '12345678901');
        $this->assertEquals($principal['Activityaddress']['flgprincipal'], 1);

        // aggiungo un nuovo indirizzo
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrementAddress = MysqlUtilityTest::getAutoIncrement($dbo, "addresses");
        $autoIncrementActivityaddress = MysqlUtilityTest::getAutoIncrement($dbo, "activityaddresses");

        // obj
        $bs = new AddressBS();
        $obj = $bs->instance();
        $obj['Address']['street'] = "StradaTest";
        $id = $bs->save($obj);

        $uabs = new ActivityaddressBS();
        $ua = $uabs->instance();
        $ua['Activityaddress']['cod'] = "MioCodTest";
        $ua['Activityaddress']['activity'] = $principal['Activityaddress']['activity'];
        $ua['Activityaddress']['address'] = $id;
        $idua = $uabs->save($ua);
        $this->assertEquals($principal['Activityaddress']['flgprincipal'], 1);
        $this->assertEquals($ua['Activityaddress']['flgprincipal'], 0);

        // cambio principal
        $ui = new ActivityaddressUI();
        $ui->setPrincipal(null, '12345678901', null, 'MioCodTest');

        $uabs = new ActivityaddressBS();
        $first = $uabs->unique($principal['Activityaddress']['id']);
        $uabs = new ActivityaddressBS();
        $second = $uabs->unique($idua);
        $this->assertEquals($first['Activityaddress']['flgprincipal'], 0);
        $this->assertEquals($second['Activityaddress']['flgprincipal'], 1);

        // resetto principal
        $ui = new ActivityaddressUI();
        $ui->setPrincipal(null, '12345678901', null, $principal['Activityaddress']['cod']);

        $uabs = new ActivityaddressBS();
        $first = $uabs->unique($principal['Activityaddress']['id']);
        $uabs = new ActivityaddressBS();
        $second = $uabs->unique($idua);
        $this->assertEquals($first['Activityaddress']['flgprincipal'], 1);
        $this->assertEquals($second['Activityaddress']['flgprincipal'], 0);

        // delete
        MysqlUtilityTest::deleteLast($dbo, "addresses", "street='StradaTest'");
        MysqlUtilityTest::deleteLast($dbo, "activityaddresses", "cod='MioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "addresses", $autoIncrementAddress);
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "activityaddresses", $autoIncrementActivityaddress);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "addresses", "street='StradaTest'");
        MysqlUtilityTest::verifyDeleted($dbo, $this, "activityaddresses", "cod='MioCodTest'");
    }

    public function testActivityreferencePrincipal() {
        $ui = new ActivityreferenceUI();
        $principal = $ui->getPrincipal(null, '12345678901', 4);
        $this->assertEquals($principal['Activityreference']['flgprincipal'], 1);

        // aggiungo un nuovo contatto
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrementReference = MysqlUtilityTest::getAutoIncrement($dbo, "contactreferences");
        $autoIncrementActivityreference = MysqlUtilityTest::getAutoIncrement($dbo, "activityreferences");

        // obj
        $bs = new ContactreferenceBS();
        $obj = $bs->instance();
        $obj['Contactreference']['val'] = "prova@prova.it";
        $id = $bs->save($obj);

        $uabs = new ActivityreferenceBS();
        $ua = $uabs->instance();
        $ua['Activityreference']['activity'] = $principal['Activityreference']['activity'];
        $ua['Activityreference']['contactreference'] = $id;
        $ua['Activityreference']['tpcontactreference'] = 4;
        $ua['Activityreference']['cod'] = 'MioCodTest';
        $idua = $uabs->save($ua);
        $this->assertEquals($principal['Activityreference']['flgprincipal'], 1);
        $this->assertEquals($ua['Activityreference']['flgprincipal'], 0);

        // cambio principal
        $ui = new ActivityreferenceUI();
        $ui->setPrincipal(null, '12345678901', null, 'MioCodTest', 4);

        $uabs = new ActivityreferenceBS();
        $first = $uabs->unique($principal['Activityreference']['id']);
        $uabs = new ActivityreferenceBS();
        $second = $uabs->unique($idua);
        $this->assertEquals($first['Activityreference']['flgprincipal'], 0);
        $this->assertEquals($second['Activityreference']['flgprincipal'], 1);

        // resetto principal
        $ui = new ActivityreferenceUI();
        $ui->setPrincipal(null, '12345678901', null, $principal['Activityreference']['cod'], 4);

        $uabs = new ActivityreferenceBS();
        $first = $uabs->unique($principal['Activityreference']['id']);
        $uabs = new ActivityreferenceBS();
        $second = $uabs->unique($idua);
        $this->assertEquals($first['Activityreference']['flgprincipal'], 1);
        $this->assertEquals($second['Activityreference']['flgprincipal'], 0);

        // delete
        MysqlUtilityTest::deleteLast($dbo, "contactreferences", "val='prova@prova.it'");
        MysqlUtilityTest::deleteLast($dbo, "activityreferences", "cod='MioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "contactreferences", $autoIncrementReference);
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "activityreferences", $autoIncrementActivityreference);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "contactreferences", "val='prova@prova.it'");
        MysqlUtilityTest::verifyDeleted($dbo, $this, "activityreferences", "cod='MioCodTest'");
    }

    public function testActivityattachmentPrincipal() {
        $ui = new ActivityattachmentUI();
        $principal = $ui->getPrincipal(null, '12345678901', 6);
        $this->assertEquals($principal['Activityattachment']['flgprincipal'], 1);

        // aggiungo un nuovo contatto
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrementAttachment = MysqlUtilityTest::getAutoIncrement($dbo, "attachments");
        $autoIncrementActivityattachment = MysqlUtilityTest::getAutoIncrement($dbo, "activityattachments");

        // obj
        $bs = new AttachmentBS();
        $obj = $bs->instance();
        $obj['Attachment']['cod'] = "NUOVO_COD";
        $id = $bs->save($obj);

        $uabs = new ActivityattachmentBS();
        $ua = $uabs->instance();
        $ua['Activityattachment']['activity'] = $principal['Activityattachment']['activity'];
        $ua['Activityattachment']['attachment'] = $id;
        $ua['Activityattachment']['tpattachment'] = 6;
        $ua['Activityattachment']['cod'] = 'MioCodTest';
        $idua = $uabs->save($ua);
        $this->assertEquals($principal['Activityattachment']['flgprincipal'], 1);
        $this->assertEquals($ua['Activityattachment']['flgprincipal'], 0);

        // cambio principal
        $ui = new ActivityattachmentUI();
        $ui->setPrincipal(null, '12345678901', null, 'MioCodTest', 6);

        $uabs = new ActivityattachmentBS();
        $first = $uabs->unique($principal['Activityattachment']['id']);
        $uabs = new ActivityattachmentBS();
        $second = $uabs->unique($idua);
        $this->assertEquals($first['Activityattachment']['flgprincipal'], 0);
        $this->assertEquals($second['Activityattachment']['flgprincipal'], 1);

        // resetto principal
        $ui = new ActivityattachmentUI();
        $ui->setPrincipal(null, '12345678901', null, $principal['Activityattachment']['cod'], 6);

        $uabs = new ActivityattachmentBS();
        $first = $uabs->unique($principal['Activityattachment']['id']);
        $uabs = new ActivityattachmentBS();
        $second = $uabs->unique($idua);
        $this->assertEquals($first['Activityattachment']['flgprincipal'], 1);
        $this->assertEquals($second['Activityattachment']['flgprincipal'], 0);

        // delete
        MysqlUtilityTest::deleteLast($dbo, "attachments", "cod='NUOVO_COD'");
        MysqlUtilityTest::deleteLast($dbo, "activityattachments", "cod='MioCodTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "attachments", $autoIncrementAttachment);
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "activityattachments", $autoIncrementActivityattachment);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "attachments", "cod='NUOVO_COD'");
        MysqlUtilityTest::verifyDeleted($dbo, $this, "activityattachments", "cod='MioCodTest'");
    }
}