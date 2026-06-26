<?php

class Database
{
    private static $instance = null;
    private PDO $connection;

    private function __construct()
    {
        $config = require __DIR__ . '/../config/database.php';

        $dsn = "pgsql:host={$config['host']};
                port={$config['port']};
                dbname={$config['dbname']}";

        $this->connection = new PDO(
            $dsn,
            $config['user'],
            $config['password']
        );

        $this->connection->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION
        );
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }
}