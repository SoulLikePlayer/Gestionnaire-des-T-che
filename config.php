<?php
class Database {
    private $db_name = 'lazare231_1';
    private $username = 'lazare231';
    private $password = 'phieYugie9ooxefe';
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=mysql.info.unicaen.fr;dbname={$this->db_name};charset=utf8", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}

