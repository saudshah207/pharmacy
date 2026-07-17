<?php

class User {
    private static $roles = [];

    private $username;
    private $password;
    private $roleId;
    private $role;
    private $isActive = 1;

    function __construct($username = null, $roleId = null) {
        $this->username = $username;
        $this->roleId = $roleId;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getRole() {
        $this->role = $this->role ?? User::$roles[$this->roleId];
        return $this->role;
    }

    public function getIsActive() {
        return $this->isActive;
    }

    public static function setRoles() {
        global $dbConnection;
        
        $result = $dbConnection->query('SELECT * FROM roles');

        if ($result) {
            while ($role = $result->fetch_object()) {
                User::$roles[$role->id] = $role->role;
            }
        }
    }

    public static function getAll() {
        $users = [];

        User::setRoles();

        global $dbConnection;
        
        $result = $dbConnection->query('SELECT * FROM users');
        
        if ($result) {
            while ($user = $result->fetch_object('User')) {
                $userRoleId = $user->roleId;
                $user->role = User::$roles[$userRoleId];

                $users[] = $user;
            }
        }

        return $users;
    }

    public static function getUserPasswordHash($db, $username) {
        $statement = $db->prepare('SELECT password FROM users WHERE isActive = 1 AND username = ?');
        $statement = bindAndExecuteStatement($statement, 's', [$username])['statement'];

        $hash = $statement->get_result()->fetch_assoc() ? $statement->get_result()->fetch_assoc()['password'] : '';

        return $hash;
    }

    public static function findUserWithCredentials($credentials) {
        $username = $credentials['username'];
        $password = $credentials['password'];

        global $dbConnection;

        $passwordHash = User::getUserPasswordHash($dbConnection, $username);

        $passwordVerified = password_verify($password, $passwordHash);

        if (!$passwordVerified) return null;

        $statement = $dbConnection->prepare("SELECT username, roleId FROM users WHERE username = ?");
        $statement = bindAndExecuteStatement($statement, 's', [$username])['statement'];

        $result = $statement->get_result()->fetch_assoc();

        return ['username' => $result['username'], 'roleId' => $result['roleId']];
    }
}