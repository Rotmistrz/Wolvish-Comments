<?php

class DatabaseConnection {
    private $pdo;
    private $host;
    private $dbname;
    private $user;
    private $password;

    public function __construct($host, $dbname, $user, $password) {
        $this->host = $host;
        $this->dbname = $dbname;
        $this->user = $user;
        $this->password = $password;
    }

    public function connect() {
        $dsn = "mysql:host=" . $this->host.";dbname=" . $this->dbname;

        try {
            $this->pdo = new PDO($dsn, $this->user, $this->password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getConnection() {
        return $this->pdo;
    }
}