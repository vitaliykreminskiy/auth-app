<?php

require_once('Database.php');
require_once('Session.php');

Session::manage();

class Operation {

    public static function getAllUsers()
    {
        // no admin access - no list of users 
        if (!$_SESSION['is_admin']) {
            return [];
        }

        $db = new Database();
        $sql = 'SELECT * FROM User';
        $dataset = [
            'name' => $name
        ];

        try {
            $result = $db->execute($sql, $dataset)->fetchAll();
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        return $result;
    }

    public static function getSingleUser($userId) 
    {
        if (!$_SESSION['is_admin']) {
            return false;
        }

        $db = new Database();
        $sql = 'SELECT * FROM User WHERE id = :userId LIMIT 1';
        $dataset = [
            'userId' => $userId
        ];

        try {
            $result = $db->execute($sql, $dataset)->fetch();
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        return $result;
    }

    public static function checkIsNameUnique($name) 
    {
        $db = new Database();
        $sql = 'SELECT COUNT(*) FROM User WHERE name = :name LIMIT 1';
        $dataset = [
            'name' => $name
        ];

        try {
            $result = $db->execute($sql, $dataset)->fetch();
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        return $result[0];
    }

    public static function addUser($name) 
    {
        if (self::checkIsNameUnique($name)) {
            return 0;
        }

        $db = new Database();
        $sql = 'INSERT INTO User SET name = :name';
        $dataset = [
            'name' => $_POST['newUserName']
        ];

        try {
            $result = $db->execute($sql, $dataset);
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        return $result->rowCount();
    }

    public static function toggleUserAttribute($attribute, $userId) {
        $availableAttributes = ['is_blocked', 'is_password_enforced'];

        if (!in_array($attribute, $availableAttributes)) {
            return false;
        }

        $db = new Database();
        $sql = 'UPDATE User SET ' . "$attribute = !$attribute" . ' WHERE id = :userId';
        $dataset = ['userId' => $userId];

        try {
            $result = $db->execute($sql, $dataset);
        } catch (Exception $e) {
            echo $e->getMessage(); exit;
        }

        return $result->rowCount();
    }

}