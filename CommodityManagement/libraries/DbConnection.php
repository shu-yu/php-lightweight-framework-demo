<?php

namespace Libraries;

use Illuminate\Database\Capsule\Manager as DB;
use Libraries\Configuration;
use Common\Errors;

class DbConnection {

    const DB_CONFIG_FILE = 'db.yaml';
    private static $db = false;

    public static function connectDb() {

        if (self::$db !== false) {
            return;
        }

        $dbConfig = Configuration::get(self::DB_CONFIG_FILE);
        if (!is_array($dbConfig) || !array_key_exists('database', $dbConfig)) {
            Log::error('fail to get db config');
            Errors::throws(Errors::ERR_CODE_DB_ERROR);
            return;
        }

        try {
            self::$db = new DB();
            self::$db->addConnection(array(
                'driver'    => $dbConfig['database']['driver'],
                'host'      => $dbConfig['database']['host'],
                'port'      => $dbConfig['database']['port'],
                'database'  => $dbConfig['database']['database'],
                'username'  => $dbConfig['database']['username'],
                'password'  => $dbConfig['database']['password'],
                'charset'   => $dbConfig['database']['charset'],
                'collation' => $dbConfig['database']['collation'],
            ));
            self::$db->setAsGlobal();
            self::$db->bootEloquent();

        } catch (\Exception $e) {
            Log::error('fail to connect db: ' . $e->getMessage());
            Errors::throws(Errors::ERR_CODE_DB_ERROR);
        }

    }

    public static function beginTransaction() {

        if (self::$db !== false) {
            try {
                DB::beginTransaction();
            } catch (\Exception $e) {
                Log::error('fail to begin db transaction: ' . $e->getMessage());
                Errors::throws(Errors::ERR_CODE_DB_ERROR);
            }
        }
    
    }

    public static function commit() {
    
        if (self::$db !== false) {
            try {
                DB::commit();
            } catch (\Exception $e) {
                Log::error('fail to commit db transaction: ' . $e->getMessage());
                Errors::throws(Errors::ERR_CODE_DB_ERROR);
            }
        }
    
    }

    public static function rollback() {
    
        if (self::$db !== false) {
            try {
                DB::rollBack();
            } catch (\Exception $e) {
                Log::error('fail to rollback db transaction: ' . $e->getMessage());
                Errors::throws(Errors::ERR_CODE_DB_ERROR);
            }
        }
    
    }

}
