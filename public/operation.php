<?php

require_once('lib/Operation.php');
require_once('lib/Session.php');

Session::manage();

if (isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action == 'addUser') {
        $result = Operation::addUser($_POST['newUserName']);
        if (!$result) {
            Session::putMessage('An error occured during process of adding new user, probably this name is already taken');
        } else {
            Session::putMessage('New user was successfully added');
        }

        header('Location: dashboard.php');
    }
}

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    if ($action == 'toggleAttribute') {
        if (!$_SESSION['is_admin']) {
            Session::putMessage('You do not have enough privileges for performing this action');
        } else {
            $result = Operation::toggleUserAttribute($_GET['attribute'], $_GET['userId']);
            if (!$result) {
                Session::putMessage('Some error occured during the operation');
            } else {
                Session::putMessage('The operation performed successfully. Changes will be applied after next login');
            }
        }

        header('Location: dashboard.php');
    }
}