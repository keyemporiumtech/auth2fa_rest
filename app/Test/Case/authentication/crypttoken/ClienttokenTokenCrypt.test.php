<?php
App::uses("ClienttokenBS", "modules/authentication/business");
App::uses("MysqlUtilityTest", "Test/utility");
App::uses("CryptingUtility", "modules/crypting/utility");

class ClienttokenTokenCryptTest extends CakeTestCase {
    public $tokenEncrypted = "ZUt0bFc3dVpXQzR0SmM5YjlGTGE2MlVCU0xWTlE4NFc4cXdYRXlZZ1QxUEZkZjBKMFRCckJ0NXFOenlSMGdXdWdwV2xpV3RZaWcvcUFKdWtSM3Y5TEplQkphcUJZd0x1dkF5SnFsQWZySmc9";

    public function testDecrypt() {
        $bs = new ClienttokenBS();
        $obj = $bs->unique(1);
        $this->assertEquals($obj['Clienttoken']['token'], $this->tokenEncrypted);

        $bs = new ClienttokenBS();
        $bs->addPropertyDao("flgDecrypt", true);
        $obj = $bs->unique(1);
        $this->assertEquals($obj['Clienttoken']['token'], CryptingUtility::decryptByType($this->tokenEncrypted, $bs->dao->decrypts['token']));
    }

    public function testEncrypt() {
        /** @var \Cake\Model\Datasource\DboSource */
        $dbo = ConnectionManager::getDataSource("default");
        $autoIncrement = MysqlUtilityTest::getAutoIncrement($dbo, "clienttokens");

        // obj
        $bs = new ClienttokenBS();
        $obj = $bs->instance();
        $obj['Clienttoken']['appname'] = "ApplicazioneTest";
        $obj['Clienttoken']['token'] = "ApplicazioneTest";

        // save
        $bs = new ClienttokenBS();
        $id = $bs->save($obj);

        // search
        $bs = new ClienttokenBS();
        $bs->addCondition("appname", "ApplicazioneTest");
        $data = $bs->unique();
        $this->assertEquals(!empty($data), true);
        $this->assertEquals($data['Clienttoken']['appname'], 'ApplicazioneTest');
        $this->assertEquals($data['Clienttoken']['token'], CryptingUtility::encryptByType('ApplicazioneTest', $bs->dao->toCrypts['token']));

        // delete
        MysqlUtilityTest::deleteLast($dbo, "clienttokens", "appname='ApplicazioneTest'");

        // reset
        MysqlUtilityTest::resetAutoIncrement($dbo, $this, "clienttokens", $autoIncrement);

        // verify reset
        MysqlUtilityTest::verifyDeleted($dbo, $this, "clienttokens", "appname='ApplicazioneTest'");
    }
}