<?php
App::uses("AppGenericUI", "modules/cakeutils/classes");
App::uses("ConnectionManager", "Model");
App::uses("DelegateUtility", "modules/cakeutils/utility");
App::uses("MessageUtility", "modules/cakeutils/utility");
App::uses("ObjPropertyEntity", "modules/cakeutils/classes");

class ExecutesqlUI extends AppGenericUI {

    function __construct() {
        parent::__construct("ExecutesqlUI");
        // $this->localefile = "cookie";
        $this->obj = null;
    }

    /**
     * Legge in ROOT . '/sql/version.txt' la versione corrente ed esegue gli script presenti in ROOT . "/sql/{$releaseName}"
     */
    function executeSql() {
        $messages = "";
        try {
            if (is_dir(ROOT . '/sql') && file_exists(ROOT . '/sql/version.txt')) {
                // version
                $releaseNameArray = explode("\n", file_get_contents(ROOT . '/sql/version.txt'));
                $releaseName = $releaseNameArray[0];
                // sql
                $dirPath = ROOT . "/sql/{$releaseName}";
                if (!is_dir($dirPath)) {
                    $messages .= "Nessuna directory $dirPath trovata\n";
                } else {
                    $files = array_diff(scandir($dirPath), array('.', '..'));
                    if (count($files) > 0) {
                        foreach ($files as $script_name) {
                            if (file_exists($dirPath . '/' . $script_name)) {
                                $info = pathinfo($dirPath . '/' . $script_name);
                                if ($info['extension'] == 'sql') {
                                    $this->Import_Script($script_name);
                                    $messages .= "File $dirPath/$script_name importato\n";
                                } else {
                                    $messages .= "$script_name estensione " . $info['extension'] . " non ammessa\n";
                                }
                            } else {
                                $messages .= "$script_name non trovato\n";
                            }
                        }
                    } else {
                        $messages .= "Nessuno script sql da eseguire\n";
                    }
                }
            } else {
                $messages .= "Nessuna directory sql o version.txt presente\n";
            }
            $this->ok();
            return $messages;

        } catch (Exception $e) {
            $this->error(mb_convert_encoding("" . __d("errors", "ERROR_GENERIC"), 'UTF-8'), $e);
            return $e->getMessage();
        }
    }

    function executeSqlByPath($dirPath = null) {
        $messages = "";
        try {
            if (!empty($dirPath) && is_dir($dirPath)) {

                $files = array_diff(scandir($dirPath), array('.', '..'));
                if (count($files) > 0) {
                    foreach ($files as $script_name) {
                        if (file_exists($dirPath . '/' . $script_name)) {
                            $info = pathinfo($dirPath . '/' . $script_name);
                            if ($info['extension'] == 'sql') {
                                $this->Import_Script($script_name);
                                $messages .= "File $dirPath/$script_name importato\n";
                            } else {
                                $messages .= "$script_name estensione " . $info['extension'] . " non ammessa\n";
                            }
                        } else {
                            $messages .= "$script_name non trovato\n";
                        }
                    }
                } else {
                    $messages .= "Nessuno script sql da eseguire\n";
                }

            } else {
                $messages .= "La directory {$dirPath} non è presente\n";
            }
            $this->ok();
            return $messages;

        } catch (Exception $e) {
            $this->error(mb_convert_encoding("" . __d("errors", "ERROR_GENERIC"), 'UTF-8'), $e);
            return $e->getMessage();
        }
    }

    function Import_Script($script_name) {

        $query = '';
        $sqlScript = file($script_name);
        // $conn = new mysqli($mysqlHostName, $mysqlUserName, $mysqlPassword, $mysqlDatabaseName);
        foreach ($sqlScript as $line) {

            $startWith = substr(trim($line), 0, 2);
            $endWith = substr(trim($line), -1, 1);

            if (empty($line) || $startWith == '--' || $startWith == '/*' || $startWith == '//') {
                continue;
            }

            $query = $query . $line;
            if ($endWith == ';') {
                /** @var \Cake\Model\Datasource\DboSource */
                $dbo = ConnectionManager::getDataSource("default");
                $data = $dbo->query($query);
            }
        }
    }

}