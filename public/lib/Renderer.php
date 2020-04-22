<?php

class Renderer {
    public static function render($view, $args = []) {
        $viewsDirectory = $_SERVER['DOCUMENT_ROOT'] . '//views/';
        try {
            require_once($viewsDirectory . $view . '.php');
        } catch (Exception $e) {
            echo 'View not found';
        }
    }
}