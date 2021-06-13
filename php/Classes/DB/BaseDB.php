<?php 

namespace Classes\DB;
use \PDO;

//Синглтон для коннекта к базе

class BaseDB 
{
    protected static $instance; 

    private function __construct() {} 
    private function __clone() {} 

    //Создаем инстанс коннекта, если еще нет, иначе возвращаем его
    public static function getInstance($host, $db, $charset, $login, $pass) 
    {
        if (self::$instance === NULL) {
            $opt = 
            [
				\PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
				\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
            ];
            try {
                self::$instance = new \PDO("mysql:host=$host;dbname=$db;charset=$charset", $login, $pass, $opt);
            }
            catch (\PDOException $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }            
        }
        return self::$instance;
    }  
}
