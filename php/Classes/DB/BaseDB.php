<?php 

namespace Classes\DB;

//Singleton pattern DB wrapper

class BaseDB 
{
    protected static $instance = []; //static instance to store an object

    private function __construct() {} //Singleton pattern object should have a single instance therefore can't be created with "new"
    private function __clone() {} // or cloned

    //creating a BaseDB object instance if it doesn't exist or returning an existing one
    public static function getInstance($host, $db, $charset, $login, $pass) 
    {
        if (self::$instance === NULL) {
            $opt  = 
            [
				PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ];
            try {
                self::$instance = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $login, $pass, $opt);
            }
            catch (PDOException $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }            
        }
        return self::$instance;
    }  
}
