<?php


class UserController {
    public static function index() {
        require __DIR__ . '/../Model/User.php';▐
        
        $users = User::getAll();

        require __DIR__ . '/../Views/users.php';
    }
}