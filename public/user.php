<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/Session.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/Operation.php');
Session::manage();

$user = Operation::getSingleUser($_GET['id']);

if (!Session::has()) {
    header('Location: index.php');
}

if (!$user) {
    echo 'No such user or you do not have access to users list';
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/views/shared/head.php'); ?>
    <title><?= $user['name'] ?></title>
</head>
<body>
    <nav class="navbar navbar-light bg-dark">
        <a class="navbar-brand" href="#">
            <div style="color: white">
                <span>Auth app</span>
                    <span> | </span>
                    <span class="text-info">Admin<span>
            </div>
        </a>
        <span class="navbar-text">
            <a class="text-light" href="auth.php?action=logout">Log out</a>
        </span>
    </nav>
    <?php if (!$_SESSION['is_admin']): ?>

    <div class="alert alert-danger">
        Sorry, this page for admin only
    </div>

    <?php exit; endif; ?>
    <?php if ($_SESSION['is_blocked']): ?>

    <div class="alert alert-danger">
        Sorry, access to your account was blocked by Admin
    </div>

    <?php exit; endif; ?>

    <h2 style="margin-top: 10px; margin-left: 10px">
        <?php if ($user['is_admin']): ?>
            <span class='fas fa-bolt text-warning' title='Admin'></span>
        <?php endif; ?>
        <span>
            <?= $user['name'] ?>
        </span>
    </h2>
    <div style="margin-top: 30px" class="container">
        <div class="row">
            <div class="col-md">
                <a class='text-center' href="operation.php?action=toggleAttribute&attribute=is_blocked&userId=<?= $user['id']; ?>">
                    <h3 style="font-size: 80px" class='fas fa-ban text-<?= $user['is_blocked'] ? 'danger' : 'muted' ?>'  title='Blocked'></h3>
                </a>
            </div>
            <div class="col-md text-center">
                <a class='text-center' href="operation.php?action=toggleAttribute&attribute=is_password_enforced&userId=<?= $user['id']; ?>">
                    <h3 style="font-size: 80px" class='fas fa-key text-<?= $user['is_password_enforced'] ? 'info' : 'muted' ?>'  title='Enforced to have strong password'></h3>
                </a>
            </div>
        </div>
    </div>
</body>