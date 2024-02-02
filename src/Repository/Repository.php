<?php

namespace Data\Repository;

use Data\Connection\PDOConnection;

abstract class Repository
{
    protected $conn;

    public function __construct()
    {
        $this->conn = new PDOConnection;
        $this->conn = $this->conn->connect();
    }

}
