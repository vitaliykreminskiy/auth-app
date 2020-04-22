<?php 

require_once('lib/Database.php');

class Auth {
    public static function check($name, $password)
    {   
        $db = new Database();
        $passwordCondition = $password ? '= :password' : 'IS NULL';
        $sql = 'SELECT * FROM User WHERE name = :name AND password ' . $passwordCondition . ' LIMIT 1';

        $dataset = ['name' => $name];

        if ($password) {
            $dataset['password'] = $password;
        }

        return $db->execute($sql, $dataset)->fetch();
    }

    private static function checkCase($character) {
        $lower = mb_strtolower($character, 'UTF-8');
        $upper = mb_strtoupper($character, 'UTF-8');

        if ($lower == $character) {
            return 'lower';
        }

        if ($upper == $character) {
            return 'upper';
        }
    }

    public static function validateStrongPassword($password) {
        $characters = str_split($password);

        $arithmeticSigns = ['-', '+', '%', '/', '*', '=', '>', '<'];

        $hasArithmeticSigns = false;
        $hasLowerCase = false;
        $hasUpperCase = false;

        foreach ($characters as $character) {
            $case = self::checkCase($character);
            if ($case == 'upper') {
                $hasUpperCase = true;
            } elseif ($case == 'lower') {
                $hasLowerCase = true;
            }

            if (in_array($character, $arithmeticSigns)) {
                $hasArithmeticSigns = true;
            }
        }

        return $hasLowerCase && $hasUpperCase && $hasArithmeticSigns;
    }

    public static function changePassword($postData) 
    {
        $allSet = isset($postData['newPassword']) && isset($postData['newPasswordConfirm']);

        if (!$allSet) {
            Session::putMessage('Please, fill up new password field');

            return 0;
        }

        if ($postData['newPassword'] != $postData['newPasswordConfirm']) {
            Session::putMessage('Passwords do not match');

            return 0;
        }

        if (strlen($postData['newPassword']) < 8) {
            Session::putMessage('The minimum password length is 8 characters');

            return 0;
        }

        if ($_SESSION['is_password_enforced']) {
            $strong = self::validateStrongPassword($postData['newPassword']);
            if (!$strong) {
                $errorMessage = 'Sorry, this password does not satisfy strong password reqirements:<br>';
                $errorMessage .= ' - New password should contain at least one upper case and one lower case characters<br>';
                $errorMessage .= ' - New password should containt arithmetic operations signs (/+-=><%)';

                Session::putMessage($errorMessage);
                
                return 0;
            }
        }

        $db = new Database();
        $passwordCondition = $postData['oldPassword'] ? '= :oldPassword' : 'IS NULL';
        $sql = 'UPDATE User SET password = :newPassword WHERE name = :name AND password ' . $passwordCondition;
        $dataset = [
            'newPassword' => md5($postData['newPassword']),
            'name' => $_SESSION['name']
        ];

        if (!empty($postData['oldPassword'])) {
            $dataset['oldPassword'] = md5($postData['oldPassword']);
        }

        try {
            $result = $db->execute($sql, $dataset);
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        return $result->rowCount();
    }
}