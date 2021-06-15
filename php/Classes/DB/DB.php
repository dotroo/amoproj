<?php 

namespace Classes\DB;
use \PDO;

//Синглтон для коннекта к базе

class DB 
{
    protected static $instance; 

    private function __construct() {} 
    private function __clone() {} 

    //Создаем инстанс коннекта, если еще нет, иначе возвращаем его
    public static function getInstance() :PDO 
    {
        $dbConf = parse_ini_file('../../../configs/db_config.ini'); //получаем конфиг БД из файла
        if (self::$instance === NULL) {
            $opt = 
            [
				\PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
				\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
            ];
            try {
                self::$instance = new \PDO("mysql:host={$dbConf['DBHOST']};dbname={$dbConf['DBNAME']};charset={$dbConf['DBHCHARSET']}", $dbConf['DBLOGIN'], $dbConf['DBPASS'], $opt);
            }
            catch (\PDOException $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }            
        }
        return self::$instance;
    }  

    public function request(string $sql, $params = NULL) :PDOStatement
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
            print "Error!: " . $e->getMessage() . "<br/>";
                die();
        }
        return $stmt;
    }
}
