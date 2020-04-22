<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/Session.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/Operation.php');
Session::manage();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/views/shared/head.php'); ?>
    <title>Dashboard</title>
</head>
<body>
    <nav class="navbar navbar-light bg-dark">
        <a class="navbar-brand" href="#">
            <div style="color: white">
                <span>Auth app</span>
                    <span> | </span>
                    <span class="text-info"><?= $_SESSION['name'] ?><span>
            </div>
        </a>
        <span class="navbar-text">
            <a href="#" style="margin-right: 10px;" class="btn btn-primary text-white" data-toggle="modal" data-target="#exampleModal">About</a>
            <a class="text-light" href="auth.php?action=logout">Log out</a>
        </span>
    </nav>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Про програму</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            Цей додаток було зроблено для лабораторної роботи 1 з дисципліни "Безпека програм та даних" <br><br>
            <b>Автор:</b> Віталій Кремінський<br>
            <b>Група:</b> ІПЗ-42, Варіант 14<br><br>
            <b>ТНЕУ - 2020</b>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрити</button>
        </div>
        </div>
    </div>
    </div>

    <?php if ($_SESSION['is_blocked']): ?>

    <div class="alert alert-danger">
        Sorry, access to your account was blocked by Admin
    </div>

    <?php exit; endif; ?>

    <!-- This is area for showing messages -->

    <?php 

    $message = Session::getMessage();

    if ($message):
    ?>

    <div class="alert alert-info">
        <?= $message ?>
    </div>  

    <?php endif; ?>

    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <!-- This is password change section -->

                <form action='auth.php' method='post'>
                    <h4>Changing password</h4>
                    <input type="hidden" name="action" value="changePassword">
                    <div class="form-group">
                        <label for="oldPassword">Old Password</label>
                        <input type="password" class="form-control" name="oldPassword">
                    </div>
                    <div class="form-group">
                        <label for="newPassword">New Password</label>
                        <input type="password" class="form-control" name="newPassword">
                    </div>
                    <div class="form-group">
                        <label for="newPassword">New Password Confirm</label>
                        <input type="password" class="form-control" name="newPasswordConfirm">
                    </div>
                    <button type="submit" class="btn btn-primary">Change</button>
                </form>
            </div>
            <div class="col">
                <!-- This section for adding new user -->
            <?php if ($_SESSION['is_admin']): ?>
                <form action='operation.php' method='post'>
                    <h4>Adding new user</h4>
                    <input type="hidden" name="action" value="addUser">
                    <div class="form-group">
                        <label for="oldPassword">User Name</label>
                        <input type="text" class="form-control" name="newUserName">
                    </div>
                    <button type="submit" class="btn btn-primary">Add</button>
                </form>
            <? endif; ?>
            </div>
            <div class="col"></div>
        </div>
        
        <!-- This section is a table with users list -->
        <hr>
        <br>
        <?php
            $users = Operation::getAllUsers();
            if (!empty($users) && $_SESSION['is_admin']):
        ?>
        <h4>List of all available users</h4>
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($users as $user): ?>
                <tr>
                    <th scope="row"><?= $user['id'] ?></th>
                    <td>
                        <a href="user.php?id=<?= $user['id']; ?>">
                            <?= $user['name'] ?>
                        </a>
                    </td>
                    <td>
                        <h3 class='fas fa-bolt text-<?= $user['is_admin'] ? 'warning' : 'white' ?>'  title='Admin'></h3>
                    </td>
                    <td>
                        <a href="operation.php?action=toggleAttribute&attribute=is_blocked&userId=<?= $user['id']; ?>">
                            <h3 class='fas fa-ban text-<?= $user['is_blocked'] ? 'danger' : 'muted' ?>'  title='Blocked'></h3>
                        </a>
                    </td>
                    <td>
                        <a href="operation.php?action=toggleAttribute&attribute=is_password_enforced&userId=<?= $user['id']; ?>">
                            <h3 class='fas fa-key text-<?= $user['is_password_enforced'] ? 'info' : 'muted' ?>'  title='Enforced to have strong password'></h3>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <tbody>
        </table>
        <?php endif; ?>
    </div>
</body>
</html>