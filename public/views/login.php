<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/Session.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/views/shared/head.php'); ?>
    <title>Auth App</title>
</head>
<body>
    <nav class="navbar navbar-light bg-dark">
        <a class="navbar-brand" href="#">
            <div style="color: white">Auth app</div>
        </a>
    </nav>
    <div class="container">
        <?php
            $message = Session::getMessage();
            if ($message):
        ?>

        <div class="alert alert-info" role="alert">
            <?= $message ?>
        </div>

        <?php endif ?>
        <div class="row">
            <div class="col"></div>
            <div class="col">    
                <form action='auth.php' method='post'>
                <input type="hidden" name="action" value="login">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Username</label>
                        <input type="text" class="form-control" id="email" name="name" aria-describedby="emailHelp">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Password</label>
                        <input type="password" class="form-control" name="password" id="password">
                    </div>
                    <button type="submit" class="btn btn-primary">Log In</button>
                </form>
            </div>
            <div class="col"></div>
        </div>
    </div>
</body>
</html>