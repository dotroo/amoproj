<?php 

class DBRequest {
    private $state;
    private $result;
    private $sqlData;

    public function __construct($address, $login, $password, $database){
        this->state = mysqli_connect($address, $login, $password, $database);
        if (this->state = false)
        {
            print("Ошибка: Невозможно подключиться к MySQL " . mysqli_connect_error());
        }
        else
        {
            return this->state;
        }
    }

    public function select($cols, $table){
        $sql = 'SELECT' . $cols . 'FROM' . $table;
        $this->result = mysqli_query($this->state, $sql);
        return $this->result;
    }

    public function insert($table, $cols){
        foreach ($cols as $col=>$data){
            $tempArray[] = $col . '=' . $data;
            $this->sqlData = implode(",", $tempArray);
        }
        $sql = 'INSERT INTO' . $this->table . 'SET' . $this->sqlData;
        $this->result = mysqli_query($this->state, $sql);
        return $this->result;
    }
}

?>