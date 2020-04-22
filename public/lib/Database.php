<?php

class Database {
    private $_instance;

    public function __construct() {
        $connection = new PDO("mysql:host=mysql;dbname=admin", 'admin', 'admin');
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->_instance = $connection;
    }

    public function execute($sql, $dataset = []) {
        try{
            $statement = $this->_instance->prepare($sql);
            $statement->execute($dataset);

            return $statement;
        } catch(PDOException $e) {
            throw new Exception('An SQL error occurred');
        }
    }
}