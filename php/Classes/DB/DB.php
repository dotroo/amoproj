<?php 

namespace Classes\DB;
use Classes\Logger\Logger;
use \PDO;

//Синглтон для коннекта к базе

class DB 
{
    protected static $instance; 

    private function __construct() {} 
    private function __clone() {} 

    //Создаем инстанс коннекта, если еще нет, иначе возвращаем его
    public static function getInstance($dbConf) :\PDO 
    {
        if (self::$instance === NULL) {
            $opt = 
            [
				\PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
				\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
            ];
            try {
                self::$instance = new \PDO(
                    "mysql:host={$dbConf['DBHOST']};dbname={$dbConf['DBNAME']};charset={$dbConf['DBCHARSET']}",
                    $dbConf['DBLOGIN'],
                    $dbConf['DBPASS'],
                    $opt
                );
            }
            catch (\PDOException $e) {
                Logger::getLogger("db_error")->log("db connection error: " . $e->getMessage());
            }            
        }
        return self::$instance;
    }  

    public static function request(string $sql, $params = NULL) :\PDOStatement
    {
        try { 
            if (!isset($params)) {
                $stmt = self::$instance->query($sql);
            } else {
               $stmt = self::$instance->prepare($sql);
               $stmt->execute($params);
            }
        }
        catch (PDOException $e) {
            Logger::getLogger("db_error")->log("db request error: " . $e->getMessage());
        }
        return $stmt;
    }
}
