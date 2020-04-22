<?php

class Session {

    static function showMessage() {
        if (isset($_SESSION['message'])) {
            $classes = ['info', 'error'];
            $class = isset($_SESSION['message_type']) ? $_SESSION['message_type'] : 0;
            echo '<p class="notification ' .$classes[$class].'">' . $_SESSION['message'] . '</p><br>';
            unset($_SESSION['message']);
        }
    }

    static function getMessage() {
        if (isset($_SESSION['message'])) {
            $message = $_SESSION['message'];
            unset($_SESSION['message']);

            return $message;
        }
    }

    static function putMessage($text, $type = 0) {
        $_SESSION['message'] = $text;
        $_SESSION['message_type'] = $type;
    }

    static function manage() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    static function has() {
        return isset($_SESSION['name']);
    }

    static function destroy() {
        if (isset($_SESSION['name'])) {
            unset($_SESSION['name']);
            unset($_SESSION['is_admin']);
        }
    }
}
