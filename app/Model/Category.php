<?php

class Category {
    private $id;
    private $name;

    private $db;

    public function __construct($dbConnection = null) {
        $this->db = $dbConnection;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $name = trim($name);

        $maxLength = 50;

        if (strlen($name) > $maxLength || $name === '') return;

        $this->name = $name;

        return true;
    }

    public static function findCategoryName($categoryId) {
        global $dbConnection;

        $statement = $dbConnection->prepare("SELECT name FROM categories WHERE id = ?");
        $statement = bindAndExecuteStatement($statement, 'i', [$categoryId])['statement'];

        $name = $statement->get_result()->fetch_assoc()['name'];

        return $name;
    }

    public static function findCategoryId($categoryName) {
        global $dbConnection;

        $statement = $dbConnection->prepare("SELECT id FROM categories WHERE name = ?");
        $statement = bindAndExecuteStatement($statement, 's', [$categoryName])['statement'];
        
        $id = $statement->get_result()->fetch_assoc()['id']; 

        return $id;
    }

    public function getAll() {
        $result = $this->db->query('SELECT * FROM categories');

        $categories = [];

        if ($result) {
            while ($category = $result->fetch_object('Category')) {
                $categories[] = $category;
            }
        }

        return $categories;
    }

    public function add($data) {
        $name = $data['name'];

        $category = new Category();
        
        if ($category->setName($name)) {
            try {
                $statement = $this->db->prepare("INSERT INTO categories (name) VALUES (?)");
                $status = bindAndExecuteStatement($statement, 's', [$name])['status'];

                return $status;
            } catch (Exception $e) {
                $_SESSION['categoryAddErrorMessage'] = 'Category names must be unique!';
            }
        }

        return false;
    }

    public function edit($data) {
        $id = $data['id']; 
        $name = $data['name'];

        $category = new Category();

        if ($category->setName($name)) {
            try {
                $statement = $this->db->prepare("UPDATE categories SET name = ? WHERE id = ?");
                $status = bindAndExecuteStatement($statement, 'si', [$name, $id])['status'];

                return $status;
            } catch (Exception $e) {
                $_SESSION['categoryEditErrorMessage'] = 'Category names must be unique!';
            }
        }

        return false;
    }

    public function delete($data) {
        $id = $data['id'];

        $statement = $this->db->prepare("DELETE FROM categories WHERE id = ?");
        $status = bindAndExecuteStatement($statement, 'i', [$id])['status'];

        return $status;
    }
}