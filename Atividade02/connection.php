<?php

class Connection
{
    private $host;
    private $dbname;
    private $username;
    private $password;
    private $pdo;

    public function __construct($host, $dbname, $username, $password)
    {
        $this->host = $host;
        $this->dbname = $dbname;
        $this->username = $username;
        $this->password = $password;
        $this->connect();
    }

    private function connect()
    {
        try {
            $url = "mysql:host={$this->host};dbname={$this->dbname}";
            $this->pdo = new PDO($url, $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erro ao conectar com o DB." . $e->getMessage());
        }
    }

    public function getConnection()
    {
        return $this->pdo;
    }
}
