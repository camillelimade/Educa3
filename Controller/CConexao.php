<?php
// Inicialize a sessão
session_start();

class CConexao
{
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "biblioteca";
    private $conn;

    public function __construct()
    {
        try {
            $this->conn = new PDO("mysql:host=$this->servername;dbname=$this->dbname", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->conn->exec("SET NAMES utf8");
        } catch (PDOException $e) {
            echo "Erro na conexão com o banco de dados: " . $e->getMessage();
        }
    }

    public function getConnection()
    {
        if ($this->conn) {
            return $this->conn;
        } else {
            throw new Exception("Conexão com o banco de dados não estabelecida");
        }
    }

    public function closeConnection()
    {
        $this->conn = null;
    }
}