<?php

namespace Data\Connection;

class PDOConnection
{
    private $host;
    private $database;
    private $user;
    private $password;
    private $driver;

    public function __construct()
    {
        // Inserir as variáveis do seu ambiente
        $this->host = 'localhost';
        $this->database = '';
        $this->user = '';
        $this->password = '';
        $this->driver = 'mysql';
    }

    public function connect()
    {
        try {
            $conn = new \PDO(
                "{$this->driver}:host={$this->host};dbname={$this->database};charset=utf8mb4",
                $this->user,
                $this->password
            );

            $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            return $conn;
        } catch (\PDOException $e) {
            die('Não foi possível solicitar a conexão com nossos servidores. Tente novamente.');
        }
    }
}
