<?php

require_once __DIR__ . '/../Model/User.php';

class AuthController {
    public static function index() {
        session_start();
        $user = $_SESSION['user'] ?? null;

        if (isset($user)) {
            $userRole = $_SESSION['user_role'];
            header("Location: /$userRole", true, 302);
            exit;
        }

        $loginSuccessful = $_SESSION['loginSuccessful'] ?? null; 

        unset($_SESSION['loginSuccessful']);

        require __DIR__ . '/../Views/login.php';
    }
    
    public static function login() {
        $user = User::findUserWithCredentials($_POST);

        session_start();

        if ($user) {
            User::setRoles();

            $_SESSION['user'] = $user['username'];

            $user = new User($user['username'], $user['roleId']);

            $userRole = $user->getRole();

            $_SESSION['user_role'] = $userRole;

            header("Location: /$userRole", true, 302);
            exit;
        }
        else {
            $_SESSION['loginSuccessful'] = false;

            header("Location: /", true, 302);
            exit;
        }
    }

    public static function redirectIfNeeded($otherRole = null) {
        session_start();
        
        $user = $_SESSION['user'] ?? null;

        $userRole = $_SESSION['user_role'] ?? null;

        if (!isset($user)) {
            header("Location: /", true, 302);
            exit;
        } else if ($userRole === $otherRole) {
            header("Location: /$userRole", true, 302);
            exit;
        }
    }

    public static function logout() {
        session_start();

        unset($_SESSION['user'], $_SESSION['user_role']);

        header("Location: /", true, 302);
        exit;
    }
}