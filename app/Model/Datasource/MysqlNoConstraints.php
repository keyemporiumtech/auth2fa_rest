<?php

/**
 * Rende disponibile un datasource Mysql che imposta il FOREIGN_KEY_CHECKS a 0 e il timezone di configurazione ad ogni nuova connessione
 *
 * @author Giuseppe Sassone
 */
App::uses('Mysql', 'Model/Datasource/Database');
App::uses('Defaults', 'Config/system');

class MysqlNoConstraints extends Mysql {

    public function connect() {
        if (parent::connect()) {
            // @@global.time_zone need super privilages
            $this->_connection->exec("SET FOREIGN_KEY_CHECKS = 0; SET @@session.time_zone = '" . Defaults::get("timezone_db") . "';");
            return true;
        }
        return false;
    }
}
