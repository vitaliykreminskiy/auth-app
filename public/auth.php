<?php

require_once('lib/Renderer.php');
require_once('lib/Session.php');
require_once('lib/Auth.php');

Session::manage();

if (isset($_GET['action'])) {
    Session::manage();
    $action = $_GET['action'];
    if ($action == 'logout') {
        session_destroy();
        header('Location: index.php');
    }
}

if (isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action == 'login') {
        $password = $_POST['password'] ? md5($_POST['password']) : NULL;
        $user = Auth::check($_POST['name'], $password);
        $correct = !empty($user);

        if (!$correct) {
            Session::putMessage('The credentials are incorrect');
            header('Location: index.php');
        } else {
            Session::manage();

            $_SESSION['name'] = $_POST['name'];
            $_SESSION['is_admin'] = $user['is_admin'];
            $_SESSION['is_blocked'] = $user['is_blocked'];
            $_SESSION['is_password_enforced'] = $user['is_password_enforced'];
    
            header('Location: dashboard.php');
        }
    }

    if ($action == 'changePassword') {
        Session::manage();
        $result = Auth::changePassword($_POST);

        if ($result) {
            Session::putMessage('The password was successfully changed');
        } elseif (!$result && !$_SESSION['message']) {
            Session::putMessage('Something went wrong, old password may be incorrect');
        }

        header('Location: dashboard.php');
    }
}
