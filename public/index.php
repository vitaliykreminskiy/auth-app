<?php

require_once('lib/Session.php');
require_once('lib/Renderer.php');
require_once('lib/Database.php');

$database = new Database();

Session::manage();

if (Session::has()) {
    header('Location: dashboard.php');
}

Renderer::render('login');