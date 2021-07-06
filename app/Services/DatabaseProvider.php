<?php

namespace Phapi\Services;

use Phalcon\Db\Adapter\Pdo\Mysql;

class DatabaseProvider {

    public static function getDbConnection(){
        $di = \Phalcon\Di::getDefault();
        $config = $di->get('config');

        $config = [
            "host"     => $config->database->host,
            "username" => $config->database->username,
            "password" => $config->database->password,
            "dbname"   => $config->database->dbname,
            "charset"  => $config->database->charset,
            "options" => [ \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION ]
        ];

        $connection = new Mysql($config);
        $connection->connect();

        return $connection;
    }

    public static function removeSqlMode($connection, $removeMode){
        $data = $connection->fetchOne("SELECT @@sql_mode as mode");
        $oldModes = explode(',', $data['mode']);

        $newModes = [];
        foreach ($oldModes as $mode) {
            if($mode == $removeMode){
                continue;
            }
            $newModes[] = $mode;
        }

        $newModesString = implode(',', $newModes);
        $connection->execute("SET sql_mode = '{$newModesString}'");

        return $oldModes;
    }

    public static function restoreSqlModes($connection, $modes){
        $newModesString = implode(',', $modes);
        $connection->execute("SET sql_mode = '{$newModesString}'");

        return true;
    }
}
