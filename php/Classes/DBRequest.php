<?php 

class DBRequest {
    private $connect;

    public function __construct(string $address, string $database, string $login, string $password) {
        try {
            $this->connect = new PDO('mysql:host=' . $address . ';dbname=' . $database, $login, $password);
        }
        catch (PDOException $e){
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function select(string $cols, string $table){
        $sel = $this->connect;
        $stmt = $sel->prepare('SELECT * FROM ' . $table);
        $stmt->execute();
        return $stmt;
    }

    public function insert($table, $cols){
        $ins = $this->connect;
        
        $ins->prepare('INSERT INTO ' . $table . '');
    }
}

?>