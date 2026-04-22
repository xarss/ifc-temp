<?php
class Database {
    private $host = "REDACTED";
    private $db_name = "pontos_do_bem";
    private $username = "pontos_do_bem";
    private $password = "REDACTED";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        error_log("Tentando conectar ao banco de dados: {$this->host}");

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8");
            error_log("Conexão com o banco de dados estabelecida com sucesso");
        } catch(PDOException $e) {
            error_log("Erro de conexão com o banco de dados: " . $e->getMessage());
            throw new PDOException("Erro de conexão com o banco de dados: " . $e->getMessage());
        }

        return $this->conn;
    }
}
