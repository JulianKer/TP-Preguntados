<?php
class MysqlObjectDatabase
{
    private $conn;
    public function __construct($host, $port, $username, $password, $database)
    {
        $this->conn = new mysqli($host, $username, $password, $database, $port);
    }

    public function query($sql){
        $result = $this->conn->query($sql);
        return  $result->fetch_all( MYSQLI_ASSOC );
    }

    public function execute($sql){
        $this->conn->query($sql);
        return $this->conn->affected_rows;
    }

    public function insertar($sql){
        return $this->conn->query($sql);
    }

    public function __destruct()
    {
        $this->conn->close();
    }
    public  function getLastInsert(){
        if ($this->conn) {
            return mysqli_insert_id($this->conn);
        } else {
            return false;
        }

        }
}
