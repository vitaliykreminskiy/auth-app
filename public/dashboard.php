<?php

require_once('lib/Session.php');
require_once('lib/Renderer.php');

session_start();

if (!Session::has()) {
    header('Location: index.php');
}

Session::manage();

Renderer::render('dashboard');

